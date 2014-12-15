var Collection = {
  html: {
    addLink: '<a href="#" class="add_tag_link">Add a tag</a>',
    removeLink : '<a href="#" class="remove-tag">delete tag</a>',
    newEntry: ''
  },
  obj: {
    $collectionsUls: {}
  },
  init : function () {
    var self = this;
    $(document).ready( function () {
      self.map();
    })
  },
  map : function () {
    var self = this;
    this.obj.$collectionsUls = $('ul.tags');
    this.obj.$collectionsUls.each( function () {
      var $collectionUl = $(this);
      var $lis = $(this).find('li');
      $lis.each(function () {
        self.addRemoveTagForm($(this));
      });
      var $lastLi = $lis.last();
      self.addTagForm($collectionUl, $lastLi);
    });
  },
  addRemoveTagForm : function ($liEntry) {
    var $removeLink = $(this.html.removeLink);
    $liEntry.append($removeLink);

    this.mapRemoveLink($liEntry);
  },
  addTagForm : function($collectionUl, $lastLi) {
    var index = $collectionUl.data('index');
    $lastLi.append(this.getWrappedLi(this.html.addLink));

    this.mapAddLink($collectionUl, $lastLi);
  },
  mapRemoveLink : function ($li) {
    $li.find('.remove-tag').click( function(e) {
      e.preventDefault();
      $(this).parent('li').remove();
      return false;
    });
  },
  mapAddLink : function ($collectionUl, $lastLi) {
    var self = this;
    $lastLi.find('.add_tag_link').click( function(e) {
      e.preventDefault();
      var $newEntry = self.getNewCreatedTag($collectionUl);
      $(this).before($newEntry);
      self.addRemoveTagForm($newEntry);
      self.mapRemoveLink($newEntry);
    });
  },
  getNewCreatedTag : function ($collectionUl) {
    if ('' == this.html.newEntry) {
      this.html.newEntry = this.getPrototypeFromUl($collectionUl);
    }
    var index = $collectionUl.data('index');
    this.html.newEntry.replace(/__name__/g, index);
    $collectionUl.data('index', index + 1);
    var $newFormLi = this.getWrappedLi($(this.html.newEntry));
    return $newFormLi;
  },
  getPrototypeFromUl : function ($ul) {
    return $ul.data('prototype');
  },
  getWrappedLi : function (aLink) {
    return $('<li></li>').append(aLink);
  }
}

//Collection.init();


var DeleteConfirm = {
  deleteLinks : [],
  init: function () {
    var deleteLinks = $('.deleteConfirm');
    deleteLinks.each( function () {
      var href = $(this).attr('href');
      $(this).bind("click", function (e) {
        e.preventDefault();
        var remove = function () {
          return confirm('do you really want to delete')
        }();
        if (remove) {
          document.location.href = href;
        }
      })
    })
  }
}

DeleteConfirm.init();