var TagCollection = {
  identifier: '.tagSelect',
  init : function () {
    var self = this;
    //$(document).ready( function () {
      self.map();
    //})
  },
  map : function () {
    //$(this.identifier).select2({
    //  placeholder: "Select report type",
    //  //allowClear: true,
    //  
    //  data: [{id: 0, text: 'story'},{id: 1, text: 'bug'},{id: 2, text: 'task'}]
    //  //tags:["red", "green", "blue"]
    //});
    $(this.identifier).select2({
      placeholder: "Select report type",
      allowClear: true,
      //data: [{id: 0, text: 'story'},{id: 1, text: 'bug'},{id: 2, text: 'task'}],
      tags:["story", "task", "repository"]
    });
  }
};
TagCollection.init();
