<script type="text/javascript">
Date.prototype.localtime = function() {
  return Math.round(this.getTime() / 1000) - this.getTimezoneOffset()*60;
}

var Todo = Backbone.Model.extend({
  urlRoot : '/todo',
  add: function(data) {
    $.ajax({
      type: "POST",
      url: this.urlRoot,
      data: data
    }).done(function() {
      Todos.trigger('load');
    });
  },
  edit: function(data) {
    $.ajax({
      type: "PUT",
      url: this.urlRoot+'/'+this.id,
      data: data
    }).done(function() {
      Todos.trigger('load');
    });
  },
  changeDate: function(date) {
    $.ajax({
      type: "PUT",
      url: this.urlRoot+'/'+this.id,
      data: {'date': date}
    }).done(function() {
      Todos.trigger('load');
    });
  },
  complete: function() {
    $.get('/todo/done/' + this.id, function(res) {
      console.log(res);
      Todos.trigger('load');
    });
  },
  uncomplete: function() {
    $.get('/todo/undone/' + this.id, function(res) {
      console.log(res);
      Todos.trigger('load');
    });
  },
  up: function() {
    $.get('/todo/up/' + this.id, function() {
      Todos.trigger('load');
    }, 'text');
  },
  down: function() {
    $.get('/todo/down/' + this.id, function(res) {
      Todos.trigger('load');
    });
  }
});

var TodoList = Backbone.Collection.extend({
  url: '/todos',
  model: Todo,
  
  load: function() {
    var self = this;
    var d = new Date();
    
    self.reset();
    if(TodosView.showUnCompleted) 
      var show_uncompleted = 1; 
    else 
      var show_uncompleted = 0;

    var params = {date: d.localtime(), uncompleted : show_uncompleted};

    $.post(self.url, params, function(data) {
      for(var i in data) {
        if(data[i]['completed'] == 1)
          data[i]['completed'] = true;
        else
          data[i]['completed'] = false;
        
        
        if(data[i]['overdue'] == 1)
          data[i]['overdue'] = true;
        else
          data[i]['overdue'] = false;
        
        data[i]['rval'] = d.localtime()+i;
        
        self.add(data[i]);
      }
      
      TodosView.trigger('show');
    }, 'json');
  },
  additem: function(data) {
    TNew = new Todo();
    TNew.add(data);
  },
  edit: function(id, data) {
    this.get(id).edit(data);
  },
  del: function(id) {
    this.get(id).destroy({success: function(model, response) {
        TodosView.trigger('show');
    }});
  },

  changeDate: function(id, date) {
    TNew = new Todo();
    TNew.id = id;
    TNew.changeDate(date);
  },
  up: function(id) {
    this.get(id).up();
  },
  down: function(id) {
    this.get(id).down();
  },
  complete: function(id) {
    this.get(id).complete();
  },
  uncomplete: function(id) {
    this.get(id).uncomplete();
  }
});

var Todos = new TodoList();
Todos.on('load', Todos.load);

var ViewTodos = Backbone.View.extend({
  showUnCompleted: true,
  id: 0,
  
  initialize: function() {
    ViewTodos.__super__.initialize.call(this);
    
    var self = this;
    
    $('#showUnCompleted').live('click', function() {
      self.showUnCompleted = true;
      Todos.load();
    });
    $('#showAll').live('click', function() {
      self.showUnCompleted = false;
      Todos.load();
    });
    
    $('#addTask').live('click', function() {
      self.showAdd();
    });
    $('#editTask').live('click', function() {
      var id = $(this).attr('idval')
      self.showEdit(id);
    });
    $('#delTask').live('click', function() {
      var id = $(this).attr('idval')
      if(confirm('Delete this task ?'))
        Todos.del(id);
    });
    $('#upTask').live('click', function() {
      var id = $(this).attr('idval')
      Todos.up(id);
    });
    $('#downTask').live('click', function() {
      var id = $(this).attr('idval')
      Todos.down(id);
    });
    $('#cTask').live('click', function() {
      var id = $(this).attr('idval')
      Todos.complete(id);
    });
    $('#ucTask').live('click', function() {
      var id = $(this).attr('idval')
      Todos.uncomplete(id);
    });
    
    $('.date').live('click', function() {
      $(this).hide();
      $date_input = $(this).next().children('.date_input');
      $date_input.parent().show();
      $date_input.val($(this).text());
      $date_input.focus();
    });
  },
  
  date_change: function() {
    var $date_input = $(this);
    var $date = $date_input.parent().prev();
    $date_input.parent().hide();
    $date.show();
    $date.html($(this).val());
    Todos.changeDate($date_input.attr('idval'), $date_input.val());
  },
  
  showTodos: function() {
    var source   = $("#tmplTodos").html();
    var template = Handlebars.compile(source);
    $('#todos').html(template({todos :Todos.toJSON()}));
    
    if(this.showUnCompleted) {
      $('#showUnCompleted').addClass('active');
      $('#showAll').removeClass('active');
    }
    else {
      $('#showAll').addClass('active');
      $('#showUnCompleted').removeClass('active');
    }
    
    var self = this;
    $('.date_input').datepicker({
      weekStart: 1,
      format: '<?=$dt_format?>',
    }).on('changeDate', function(ev) {
      self.date_change.call(this);
    });
  },
  showDialog: function(data) {
  var self = this;
    var source   = $("#tmplDialog").html();
    var template = Handlebars.compile(source);
    $('#dialog').html(template(data));
    $('#dialog').modal();
    
    $('#dialog #close').click( function() {
      $('#dialog').modal('hide');
    });
    
    $('#dialog #save').click( function() {
      var data = {
        'date': $('#date').val(),
        'name': $('#name').val()
      };
      if(self.id == 0)
        Todos.additem(data)
      else
        Todos.edit(self.id, data);
      $('#dialog').modal('hide');
    });
    
    var self = this;
    $('#dialog .date_input').datepicker({
      weekStart: 1,
      format: '<?=$dt_format?>'
    });
  },
  showAdd: function() {
    this.id = 0;
    var data = {header: 'New task', date: '<?=$current_date?>', text: ''};
    this.showDialog(data);
  },
  showEdit: function(id) {
    this.id = id;
    var data = Todos.get(id).toJSON();
    data['header'] = 'Edit task';
    this.showDialog(data);
  }
});

var TodosView = new ViewTodos();
TodosView.on('show', TodosView.showTodos);
  
$(document).ready(function() {
  Todos.load(false);
});
</script>

<style>
  #todos {
    width: 980px;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
  .row div {
    height: 32px;
    text-align: center;
  }
  
  .text {
    letter-spacing: 1px;
  }
  .date {
    letter-spacing: 1px;
    font-weight: bold;
    cursor: pointer;
  }
  .date_input {
    width: 125px;
  }
  .completed .text, .completed .date {
    color: silver;
  }
  .overdue .text, .overdue .date {
    color: red;
  }
  
  #name {
    width: 98%;
    height: 100px;
  }
  
  #okTask, #nokTask {
    margin-right: 24px;
  }
</style>

<script id="tmplDialog" type="text/x-handlebars-template">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3>{{header}}</h3>
    </div>
    <div class="modal-body">
        <label for="date">Date</label>
        <input name="date" class="date_input" id="date" type="text" value="{{date}}"/>
        <label for="text">Text</label>
        <textarea name="name" id="name">{{name}}</textarea>
    </div>
    <div class="modal-footer">
      <a href="#" class="btn" id="close">Close</a>
      <a href="#" class="btn btn-primary" id="save">Save changes</a>
    </div>
</script>

<script id="tmplTodos" type="text/x-handlebars-template">
  <div class="btn-group" style="margin-bottom: 12px; text-align: center;">
    <a id="showUnCompleted" class="btn btn-info active"> Show uncompleted </a>
    <a id="showAll" class="btn btn-info"> Show all </a>
  </div>
  
  {{#each todos}}
    <div class="row {{#if completed}}completed{{/if}} {{#if overdue}}overdue{{/if}}">
      
      <div class="span3">
        {{#if completed}}
        <a class="btn btn-danger" id="ucTask" idval="{{id}}" title="Uncomplete"><span class="icon-thumbs-down"></span></a>
        {{else}}
        <a class="btn btn-success" id="cTask" idval="{{id}}" title="Complete"><span class="icon-thumbs-up"></span></a>
        {{/if}}
        <a class="btn btn-success" id="upTask" idval="{{id}}" title="UP"><span class="icon-arrow-up"></span></a>
        <a class="btn btn-success" id="downTask" idval="{{id}}" title="DOWN"><span class="icon-arrow-down"></span></a>
      </div>
      
      <div class="span4 text">{{name}}</div>
      <div class="span2 date">{{date}}</div>
      <div class="span2" style="display: none;"><input class="date_input" id="dt_{{rval}}" idval="{{id}}"/></div>
      <div class="span2">
        <a class="btn btn-success" id="editTask" idval="{{id}}" title="Edit"><span class="icon-pencil"></span></a>
        <a class="btn btn-danger" id="delTask" idval="{{id}}" title="Delete"><span class="icon-trash"></span></a>
      </div>
    </div>
  {{/each}}
  <div style="width: 100%; text-align: center;">
    <a class="btn btn-warning" id="addTask"> <span class="icon-file"></span> Add new task </a>
  </div>

</script>

<div id="todos"></div>
<div id="dialog" class="modal hide fade"></div>