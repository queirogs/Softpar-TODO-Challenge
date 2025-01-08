<template>
    <q-page class="bg-gray-100 p-5 relative">
      <div class="max-w-screen-md mx-auto">

        <!-- Formulário da tarefa -->
        <q-card class="p-4 mb-6 rounded-lg">
          <q-card-section>
            <div class="flex justify-center text-3xl mb-4 text-[#21316C] font-bold">
              Nova Tarefa
            </div>
            <q-input
              v-model="newTask.title"
              label="Título da tarefa"
              outlined
              dense
              maxlength="255"
              counter
              class="mb-4"
            />
            <q-input
              v-model="newTask.description"
              label="Descrição da tarefa"
              outlined
              dense
              type="textarea"
              maxlength="255"
              counter
              class="mb-4"
            />
            <q-btn @click="addTask" label="Adicionar tarefa" color="primary" class="w-full" />
          </q-card-section>
        </q-card>

        <!-- Filtro e ordenação -->
        <div class="flex flex-row justify-start mb-4 gap-4">
          <q-select
            v-model="sortOption"
            :options="sortOptions"
            label="Ordenar por"
            outlined
            dense
            class="w-[12vw]"
          />
          <q-select
            v-model="filterStatus"
            :options="statuses"
            label="Filtrar por status"
            outlined
            dense
            class="w-[12vw]"
          />
          <q-input
            v-model="filterDate"
            type="date"
            label="Filtrar por data"
            outlined
            dense
            class="w-[12vw]"
          />
        </div>

        <!-- Listar Tarefas -->
        <q-list>
          <q-item
            v-for="task in filteredAndSortedTasks"
            :key="task.id"
            :class="`flex flex-col relative mb-4 border-l-8 shadow-lg rounded-lg bg-white ${task.status === 'todo' ? 'border-[#C10015]' : task.status === 'doing' ? 'border-[#31CCEC]' : 'border-[#21BA45]'}`"
          >
            <q-item-section bordered>
              <div class="flex items-center justify-between py-4">
                <div v-if="!task.isEditing">
                  <div class="flex row gap-5">
                    <h3 class="text-xl font-semibold text-gray-800 self-center">{{ task.title }}</h3>
                    <q-btn-dropdown flat dense :label="task.status" class="mr-2">
                      <q-list>
                        <q-item
                          v-for="status in statuses"
                          :key="status"
                          clickable
                          @click="updateStatus(task, status)"
                        >
                          <q-item-section>{{ status }}</q-item-section>
                        </q-item>
                      </q-list>
                    </q-btn-dropdown>
                  </div>
                  <div class="flex flex-row">
                    <div class="text-xs text-gray-400 my-5 flex gap-3">
                      Criado: {{ formatDate(task.time_creation) }}
                      <div v-if="task.status == 'done'" class="text-[#21BA45] text-bold">
                        Finalizado: {{ formatDate(task.time_conclusion) }}
                      </div>
                    </div>
                  </div>
                  <p class="text-sm text-gray-500">{{ task.description }}</p>
                </div>
                <div v-else class="w-full">
                  <h3 class="text-2xl font-semibold text-gray-800 mb-4 ml-1">Editar tarefa</h3>
                  <q-input
                    v-model="task.title"
                    label="Editar Título"
                    outlined
                    dense
                    maxlength="255"
                    counter
                    class="mb-4"
                  />
                  <q-input
                    v-model="task.description"
                    label="Editar Descrição"
                    outlined
                    dense
                    type="textarea"
                    maxlength="255"
                    counter
                    class="mb-4"
                  />
                  <q-btn @click="saveTask(task)" label="Salvar" color="primary" flat class="mr-2" />
                  <q-btn @click="task.isEditing = false" label="Cancelar" flat />
                </div>
              </div>

              <div class="absolute right-0 top-0 m-[1vw]">
                <q-btn icon="edit" icon-color="#21316C" flat dense @click="task.isEditing = true" />
                <q-btn icon="delete" icon-color="#21316C" flat dense color="negative" @click="deleteTask(task.id)" />
              </div>
              <q-btn class="self-start flex mb-4 text-[#21316C]" label="Adicionar subtarefa" flat dense icon="add" @click="task.showSubtaskInput = !task.showSubtaskInput" />
            </q-item-section>

            <!-- Criar subtarefa -->
            <q-item-section class="pl-4">
              <div v-if="task.showSubtaskInput">
                <h3 class="text-xl font-semibold text-gray-800 mb-2 ml-1">Adicionar subtarefa</h3>
                <q-input
                  v-model="task.newSubtaskTitle"
                  label="Título da subtarefa"
                  outlined
                  dense
                  maxlength="255"
                  counter
                  class="mb-2"
                />
                <q-input
                  v-model="task.newSubtaskDescription"
                  label="Descrição da subtarefa"
                  outlined
                  dense
                  type="textarea"
                  maxlength="255"
                  counter
                  class="mb-2"
                />
                <q-btn @click="addSubtask(task)" label="Adicionar subtarefa" color="primary" class="mb-2" />
              </div>
            </q-item-section>

            <!-- Listar subtarefa -->
            <q-list v-if="task.subtasks && task.subtasks.length" class="pl-4">
              <q-item
                v-for="subtask in task.subtasks"
                :key="subtask.id"

            :class="`flex flex-col relative mb-4 border-l-8 shadow-lg rounded-lg bg-white ${subtask.status === 'todo' ? 'border-[#C10015]' : subtask.status === 'doing' ? 'border-[#31CCEC]' : 'border-[#21BA45]'}`"
              >
                <q-item-section>
                  <div class="flex items-center justify-between">
                    <div class="w-full">
                      <div v-if="!subtask.isEditing">
                        <div class="flex row gap-5">
                          <h4 class="text-xl font-semibold text-gray-700 self-center">{{ subtask.title }}</h4>
                          <q-btn-dropdown flat dense :label="subtask.status" class="mr-2">
                            <q-list>
                              <q-item
                                v-for="status in statuses"
                                :key="status"
                                clickable
                                @click="updateStatus(subtask, status)"
                              >
                                <q-item-section>{{ status }}</q-item-section>
                              </q-item>
                            </q-list>
                          </q-btn-dropdown>
                        </div>
                        <div class="flex flex-row">
                          <div class="text-xs text-gray-400 mb-5 flex gap-3">
                            Criado: {{ formatDate(task.time_creation) }}
                            <div v-if="subtask.status == 'done'" class="text-[#21BA45] text-bold">
                              Finalizado: {{ formatDate(task.time_conclusion) }}
                            </div>
                          </div>
                        </div>
                        <p class="text-xs text-gray-500">{{ subtask.description }}</p>
                      </div>

                      <div v-else class="w-full">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4 ml-1">Editar subtarefa</h3>
                        <q-input
                          v-model="subtask.title"
                          label="Editar título"
                          outlined
                          dense
                          maxlength="255"
                          counter
                          class="mb-2"
                        />
                        <q-input
                          v-model="subtask.description"
                          label="Editar descrição"
                          outlined
                          dense
                          type="textarea"
                          maxlength="255"
                          counter
                          class="mb-2"
                        />
                        <q-btn @click="saveTask(subtask)" label="Salvar" color="primary" flat class="mr-2" />
                        <q-btn @click="subtask.isEditing = false" label="Cancelar" flat />
                      </div>
                    </div>
                    <div class="flex items-center absolute right-0 top-0 m-[1vw]">
                      <q-btn icon="edit" flat dense @click="subtask.isEditing = true" />
                      <q-btn icon="delete" flat dense color="negative" @click="deleteTask(subtask.id)" />
                    </div>
                  </div>
                </q-item-section>
              </q-item>
            </q-list>
          </q-item>
        </q-list>
      </div>
    </q-page>
  </template>

  <script>
  import axios from "axios";

  export default {
    data() {
      return {
        tasks: [],
        newTask: {
          title: "",
          description: "",
        },
        statuses: ["todo", "doing", "done"],
        filterStatus: null,
        filterDate: null,
        sortOption: null,
        sortOptions: ["Date", "Title"],
      };
    },
    computed: {
      filteredAndSortedTasks() {
        let filteredTasks = this.tasks;

        if (this.filterStatus) {
          filteredTasks = filteredTasks.filter(
            (task) => task.status === this.filterStatus
          );
        }

        if (this.filterDate) {
          filteredTasks = filteredTasks.filter(
            (task) => task.time_creation.split("T")[0] === this.filterDate
          );
        }

        if (this.sortOption) {
          if (this.sortOption === "Date") {
            filteredTasks.sort(
              (a, b) => new Date(a.time_creation) - new Date(b.time_creation)
            );
          } else if (this.sortOption === "Title") {
            filteredTasks.sort((a, b) => a.title.localeCompare(b.title));
          }
        }

        return filteredTasks;
      },
    },
    methods: {
      formatDate(date) {
        return new Date(date).toLocaleString();
      },

      getTaskBackground(status) {
        return {
          "bg-green-100": status === "done",
          "bg-blue-100": status === "doing",
          "bg-red-100": status === "todo",
        };
      },

      async fetchTasks() {
        try {
          const response = await axios.get("http://127.0.0.1:8000/api/tasks");
          const tasks = response.data.map((task) => ({
            ...task,
            subtasks: [],
            isEditing: false,
            showSubtaskInput: false,
            newSubtaskTitle: "",
            newSubtaskDescription: "",
          }));
          tasks.forEach((task) => {
            if (task.parent_task_id) {
              const parent = tasks.find((t) => t.id === task.parent_task_id);
              if (parent) {
                parent.subtasks.push(task);
              }
            }
          });
          this.tasks = tasks.filter((task) => !task.parent_task_id);
        } catch (error) {
          console.error("Error fetching tasks:", error);
        }
      },

      async addTask() {
        try {
          await axios.post("http://127.0.0.1:8000/api/tasks", {
            title: this.newTask.title,
            description: this.newTask.description,
            time_creation: new Date().toISOString(),
            status: "todo",
            parent_task_id: null,
          });
          this.newTask.title = "";
          this.newTask.description = "";
          await this.fetchTasks();
        } catch (error) {
          console.error("Error adding task:", error);
        }
      },

      async addSubtask(parent) {
        try {
          await axios.post("http://127.0.0.1:8000/api/tasks", {
            title: parent.newSubtaskTitle,
            description: parent.newSubtaskDescription,
            time_creation: new Date().toISOString(),
            status: "todo",
            parent_task_id: parent.id,
          });
          parent.newSubtaskTitle = "";
          parent.newSubtaskDescription = "";
          parent.showSubtaskInput = false;
          await this.fetchTasks();
        } catch (error) {
          console.error("Error adding subtask:", error);
        }
      },

      async updateStatus(task, status) {
        try {
            const updatedTask = { ...task, status };

            // Atualiza o tempo de conclusão se o status for "done"
            if (status === "done") {
            updatedTask.time_conclusion = new Date().toISOString();
            } else {
            updatedTask.time_conclusion = null;
            }

            // Atualiza a tarefa pai
            await axios.put(`http://127.0.0.1:8000/api/tasks/${task.id}`, updatedTask);

            // Atualiza a lista de tarefas após as alterações
            await this.fetchTasks();
        } catch (error) {
            console.error("Error updating status:", error);
        }
        },

      async saveTask(task) {
        try {
          await axios.put(`http://127.0.0.1:8000/api/tasks/${task.id}`, {
            title: task.title,
            description: task.description,
            status: task.status,
            time_creation: task.time_creation,
            time_conclusion: task.time_conclusion,
            parent_task_id: task.parent_task_id
          });
          await this.fetchTasks();
        } catch (error) {
          console.error("Error saving task:", error);
        }
      },

      async deleteTask(id) {
        try {
          await axios.delete(`http://127.0.0.1:8000/api/tasks/${id}`);
          await this.fetchTasks();
        } catch (error) {
          console.error("Error deleting task:", error);
        }
      },

      applyFiltersAndSorting() {
        return this.filteredAndSortedTasks;
      },
    },

    async mounted() {
      await this.fetchTasks();
    },
  };
  </script>

  <style>
  /* All custom styles removed as Tailwind CSS is being used */
  </style>
