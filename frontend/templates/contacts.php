<script type="text/javascript">
Date.prototype.localtime = function() {
  return Math.round(this.getTime() / 1000) - this.getTimezoneOffset()*60;
}

var Contact = Backbone.Model.extend({
  urlRoot : '/contact',
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
  }
});

var ContactsList = Backbone.Collection.extend({
  url: '/contacts',
  model: Contact,
  
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
  del: function(id) {
    this.get(id).destroy({success: function(model, response) {
        TodosView.trigger('show');
    }});
  }
});

var Contacts = new ContactsList();
Contacts.on('load', Contacts.load);

var ViewContacts = Backbone.View.extend({
  showUnCompleted: true,
  id: 0,
  
  initialize: function() {
    ViewContacts.__super__.initialize.call(this);
    
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

var ContactsView = new ViewContacts();
ContactsView.on('show', ContactsView.showTodos);
  
$(document).ready(function() {
  Contacts.load(false);
});
</script>

<style>

</style>

<table id="MyGrid" class="table table-bordered datagrid">
 <thead>
 <tr>
  <th>
   <span class="datagrid-header-title">Geographic Data Sample</span>
   <div class="datagrid-header-left">
 
   </div>
   <div class="datagrid-header-right">
	<div class="input-append search">
	 <input type="text" class="input-medium" placeholder="Search"><button class="btn"><i class="icon-search"></i></button>
	</div>
   </div>
  </th>
 </tr>
 </thead>
 
 <tfoot>
 <tr>
  <th>
   <div class="datagrid-footer-left" style="display:none;">
	<div class="grid-controls">
	 <span><span class="grid-start"></span> - <span class="grid-end"></span> of <span class="grid-count"></span></span>
	 <select class="grid-pagesize"><option>10</option><option>20</option><option>50</option><option>100</option></select>
	 <span>Per Page</span>
	</div>
   </div>
   <div class="datagrid-footer-right" style="display:none;">
	<div class="grid-pager">
	 <button class="btn grid-prevpage"><i class="icon-chevron-left"></i></button>
	 <span>Page</span>
	 <div class="input-append dropdown combobox">
	  <input class="span1" type="text"><button class="btn" data-toggle="dropdown"><i class="caret"></i></button>
	  <ul class="dropdown-menu"></ul>
	 </div>
	 <span>of <span class="grid-pages"></span></span>
	 <button class="btn grid-nextpage"><i class="icon-chevron-right"></i></button>
	</div>
   </div>
  </th>
 </tr>
 </tfoot>
</table>



<div id="dialog" class="modal hide fade"></div>