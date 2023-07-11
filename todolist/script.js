$(document).ready(function() {
    // Carrega as tarefas existentes ao carregar a página
    loadTasks();
  
    // Submete o formulário para adicionar uma nova tarefa
    $('#task-form').submit(function(event) {
      event.preventDefault();
      var taskInput = $('#task-input');
  
      if (taskInput.val().trim() !== '') {
        addTask(taskInput.val());
        taskInput.val('');
      }
    });
  
    // Função para carregar as tarefas existentes
    function loadTasks() {
      $.ajax({
        url: 'tasks.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          var taskList = $('#task-list');
          taskList.empty();
  
          $.each(response, function(index, task) {
            var taskItem = $('<li class="task-item"></li>');
            taskItem.text(task.name);
  
            var deleteButton = $('<button>Concluído</button>');
            deleteButton.click(function() {
              deleteTask(task.id);
            });
  
            taskItem.append(deleteButton);
            taskList.append(taskItem);
          });
        },
        error: function() {
          alert('Erro ao carregar as tarefas.');
        }
      });
    }
  
    // Função para adicionar uma nova tarefa
    function addTask(name) {
      $.ajax({
        url: 'tasks.php',
        type: 'POST',
        data: {
          name: name
        },
        success: function() {
          loadTasks();
        },
        error: function() {
          alert('Erro ao adicionar a tarefa.');
        }
      });
    }
  
    // Função para excluir uma tarefa
    function deleteTask(id) {
      $.ajax({
        url: 'tasks.php',
        type: 'DELETE',
        data: {
          id: id
        },
        success: function() {
          loadTasks();
        },
        error: function() {
          alert('Erro ao excluir a tarefa.');
        }
      });
    }
  });
  