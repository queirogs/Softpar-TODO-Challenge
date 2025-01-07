<template>
  <q-page class="bg-gray-100">
    <div class="max-w-screen-md mx-auto p-6">
      <!-- Caixa do Formulário com Título -->
      <q-form class="bg-white rounded-lg p-6 shadow-md" @submit="addTask">
        <div class="text-center mb-6">
          <b class="text-4xl text-[#21316C]">LISTA DE TAREFAS</b>
        </div>

        <!-- Inputs do Formulário -->
        <q-input class="w-full mb-4" v-model="newTask.title" label="Título da Tarefa" outlined required dense />
        <q-input class="w-full mb-4" v-model="newTask.description" label="Descrição" outlined required dense />

        <!-- Botão de Criar Tarefa -->
        <q-btn class="mt-4" type="submit" color="primary" label="Criar Tarefa" />
      </q-form>

      <!-- Filtros e Ordenação -->
      <div class="flex justify-between mt-6">
        <q-select
          v-model="filterStatus"
          :options="statuses"
          label="Filtrar por Status"
          dense
          outlined
          class="w-1/2"
          placeholder="Todos os Status"
        />

        <q-select
          v-model="sortBy"
          :options="sortOptions"
          label="Ordenar por"
          dense
          outlined
          class="w-1/2"
        />
      </div>

      <!-- Lista de Tarefas -->
      <q-list class="space-y-4 mt-8">
        <q-item
          v-for="(task, index) in sortedTasks"
          :key="index"
          :class="`bg-white p-5 rounded-lg shadow-md border-2 ${task.status === 'to do' ? 'border-[#C10015]' : task.status === 'doing' ? 'border-[#31CCEC]' : 'border-[#21BA45]'}`"
        >
          <q-item-section>
            <div class="flex justify-between items-center">
              <div class="flex flex-col flex-grow">
                <div v-if="task.isEditing">
                  <q-input v-model="task.title" label="Editar título" outlined dense class="mb-4" />
                  <q-input v-model="task.description" label="Editar descrição" outlined dense class="mb-4" />
                  <q-btn @click="saveTask(index)" color="primary" label="Salvar" />
                </div>
                <div v-else>
                  <b class="text-xl">{{ task.title }}</b>
                  <p class="text-sm text-gray-600">{{ task.description }}</p>
                  <p class="text-xs text-gray-400"><strong>Criado em:</strong> {{ task.createdAt }}</p>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <!-- Status da Tarefa -->
                <q-select
                  v-model="task.status"
                  :options="statuses"
                  dense
                  outlined
                  hide-dropdown-icon
                  class="w-24"
                  label="Status"
                  @input="updateStatus(index, task.status)"
                />
                <q-btn dense color="primary" @click="editTask(index)" icon="edit" />
                <q-btn dense color="negative" @click="deleteTask(index)" icon="delete" />
              </div>
            </div>

            <!-- Botão de Adicionar Subtarefa -->
            <div class="text-left">
              <q-btn
                dense
                color="secondary"
                @click="addSubtask(index)"
                icon="add"
                label="Adicionar Subtarefa"
                class="text-left p-2"
              />
            </div>

            <!-- Lista de Subtasks -->
            <q-list class="mt-4 space-y-4">
              <q-item v-for="(subtask, subIndex) in task.subtasks" :key="subIndex" class="bg-gray-50 p-4 rounded-md shadow-sm">
                <q-item-section>
                  <div class="flex justify-between items-center">
                    <div class="flex flex-col flex-grow">
                      <div v-if="subtask.isEditing">
                        <q-input v-model="subtask.title" label="Editar título" outlined dense class="mb-4" />
                        <q-input v-model="subtask.description" label="Editar descrição" outlined dense class="mb-4" />
                        <q-btn @click="saveSubtask(index, subIndex)" color="primary" label="Salvar" />
                      </div>
                      <div v-else>
                        <strong>{{ subtask.title }}</strong>
                        <p class="text-sm text-gray-600">{{ subtask.description }}</p>
                        <p class="text-xs text-gray-400"><strong>Status:</strong> {{ subtask.status }}</p>
                      </div>
                    </div>
                    <div class="flex gap-2">
                      <q-select
                        v-model="subtask.status"
                        :options="statuses"
                        dense
                        outlined
                        hide-dropdown-icon
                        class="w-24"
                        label="Status"
                        @input="updateSubtaskStatus(index, subIndex, subtask.status)"
                      />
                      <q-btn dense color="primary" @click="editSubtask(index, subIndex)" icon="edit" />
                      <q-btn dense color="negative" @click="deleteSubtask(index, subIndex)" icon="delete" />
                    </div>
                  </div>
                </q-item-section>
              </q-item>
            </q-list>
          </q-item-section>
        </q-item>
      </q-list>
    </div>
  </q-page>
</template>

<script>
import { ref, computed } from "vue";

export default {
  name: "TodoApp",
  setup() {
    const tasks = ref([]);
    const newTask = ref({
      title: "",
      description: "",
      status: "to do",
      createdAt: new Date().toLocaleString(),
      completedAt: null,
      subtasks: [],
    });
    const statuses = ["to do", "doing", "done"];
    const sortOptions = ["Data", "Título"];
    const filterStatus = ref(""); // Filtro por status
    const sortBy = ref(sortOptions[0]); // Ordenação inicial por data

    const addTask = () => {
      tasks.value.push({ ...newTask.value });
      newTask.value = {
        title: "",
        description: "",
        status: "to do",
        createdAt: new Date().toLocaleString(),
        completedAt: null,
        subtasks: [],
      };
    };

    const editTask = (index) => {
      const task = tasks.value[index];
      task.isEditing = true;
    };

    const saveTask = (index) => {
      const task = tasks.value[index];
      task.isEditing = false;
    };

    const deleteTask = (index) => {
      if (confirm("Tem certeza que deseja excluir esta tarefa?")) {
        tasks.value.splice(index, 1);
      }
    };

    const addSubtask = (taskIndex) => {
      const task = tasks.value[taskIndex];
      task.subtasks.push({
        title: "",
        description: "",
        status: "to do",
        createdAt: new Date().toLocaleString(),
        completedAt: null,
        isEditing: true,
      });
    };

    const editSubtask = (taskIndex, subtaskIndex) => {
      const subtask = tasks.value[taskIndex].subtasks[subtaskIndex];
      subtask.isEditing = true;
    };

    const saveSubtask = (taskIndex, subtaskIndex) => {
      const subtask = tasks.value[taskIndex].subtasks[subtaskIndex];
      subtask.isEditing = false;
    };

    const deleteSubtask = (taskIndex, subtaskIndex) => {
      const task = tasks.value[taskIndex];
      task.subtasks.splice(subtaskIndex, 1);
    };

    const updateStatus = (taskIndex, status) => {
      tasks.value[taskIndex].status = status;
    };

    const updateSubtaskStatus = (taskIndex, subtaskIndex, status) => {
      tasks.value[taskIndex].subtasks[subtaskIndex].status = status;
    };

    const filteredTasks = computed(() => {
      if (filterStatus.value) {
        return tasks.value.filter(task => task.status === filterStatus.value);
      }
      return tasks.value;
    });

    const sortedTasks = computed(() => {
      const tasksToSort = filteredTasks.value;
      if (sortBy.value === "Data") {
        return tasksToSort.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
      } else {
        return tasksToSort.sort((a, b) => a.title.localeCompare(b.title));
      }
    });

    return {
      tasks,
      newTask,
      statuses,
      filterStatus,
      sortBy,
      sortOptions,
      addTask,
      editTask,
      saveTask,
      deleteTask,
      addSubtask,
      editSubtask,
      saveSubtask,
      deleteSubtask,
      updateStatus,
      updateSubtaskStatus,
      sortedTasks,
    };
  },
};
</script>
