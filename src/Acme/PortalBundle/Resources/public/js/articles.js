var self;

Articles = {
  regexStr: '',
  $regex : {},
  articles: {},
  articlesStrArr: [],
  $articlesArr: {},
  selectivityList: {},
  init : function () {
    self = this;
    $(document).ready( function () {
      self.applyHandlers();
    });
  },
  applyHandlers : function () {
    this.fillVars();
    this.initSelectivity();
    this.applyEventHandler();
  },
  fillVars : function () {
    this.$articlesArr = $('.article');
    var len = this.$articlesArr.length;
    this.$articlesArr.each( function () {
      var identifier = $(this).find('input').eq(1).val();
      self.articles[identifier] = $(this);
    });
    this.$regex = $('.regex');
    this.$articlesArr.each( function() {
      self.articlesStrArr.push($(this).find('input').eq(1).val());
    });
    //$('.regex .update').click( function (e) {
    //  self.handleRegex(e);
    //});
  },
  initSelectivity : function () {
    $('#select_tags').selectivity({
      items: self.articlesStrArr,
      tokenSeparators: [' '],
      multiple: true,
      placeholder: 'Type to show specific articles only',
    });
    $('#select_tags').on('change', function (res) {
      if (undefined != res.added) {
        self.selectivityList[res.added.id] = true;
      } else if (undefined != res.removed) {
        delete self.selectivityList[res.removed.id];
      }
      self.handleSelectivityInput();
    });
  },
  applyEventHandler : function () {
    self = this;
    this.$regex.find('.regexInput').first().on('focus', function () {
      var $regexTextInputField = $(this);
      $(this).keyup(function(e) {
        self.handleRegex(e);
        if(e.which == 13) {
          e.preventDefault();
        }
      });
    });
  },
  handleSelectivityInput : function () {
    if (1 > Object.keys(this.selectivityList).length) {
      this.$articlesArr
        .css('display', 'block')
        .css('float', 'none')
      ;
      return;
    }
    this.$articlesArr
        .css('display', 'none')
        .css('float', 'none')
    ;
    for (var identifier in this.articles) {
      if (undefined != this.selectivityList[identifier]) {
        this.articles[identifier]
          .css('display', 'block')
          .css('float', 'left')
        ;
      }
    }
  },
  handleRegex : function (e) {
    this.regexStr = this.$regex.find('input').eq(0).val();
    if (0 < this.regexStr.length) {
      this.searchWithRegex();  
    } else {
      this.$articlesArr
        .css('display', 'block')
        .css('float', 'none')
    }
  },
  searchWithRegex : function () {
    var counter = 0;
    this.$articlesArr
      .css('display', 'none')
      .css('float', 'none')
    ;
    for (var pos = 0; pos < this.articlesStrArr.length; pos++) {
      var articleName = this.articlesStrArr[pos];
      var regex = new RegExp(this.regexStr, 'gi');
      if (true == regex.test(articleName)) {
        this.showHits(pos);
      }
    }
  },
  showHits : function (pos) {
    $(this.$articlesArr.get(pos))
      .css('display', 'block')
      .css('float', 'left')
    ;
  }
};

var articles = Object.create(Articles);
articles.init();