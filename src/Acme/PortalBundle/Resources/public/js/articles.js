var self;

Articles = {
  regexStr: '',
  $regex : {},
  articlesStrArr: [],
  $articlesArr: {},
  init : function () {
    self = this;
    $(document).ready( function () {
      self.applyHandlers();
    });
  },
  applyHandlers : function () {
    this.applyRegexHandler();
    this.applyEventHandler();
  },
  applyEventHandler : function () {
    self = this;
    this.$regex.find('input').first().on('focus', function () {
      var $regexTextInputField = $(this);
      console.log("regex input clicked");
      $(document).keyup(function(e) {
        console.log("typed letter");
        self.handleRegex(e);
        if(e.which == 13) {
          e.preventDefault();
          console.log("regex enter confirmed");
        }
      });
    });
  },
  applyRegexHandler : function () {
    this.$articlesArr = $('.article');
    this.$regex = $('.regex');
    this.$articlesArr.each( function() {
      self.articlesStrArr.push($(this).find('input').eq(1).val());
    });
    console.log('this.articlesStrArr', this.articlesStrArr);
    //$('.regex .update').click( function (e) {
    //  self.handleRegex(e);
    //});
  },
  handleRegex : function (e) {
    this.regexStr = this.$regex.find('input').eq(0).val();
    if (0 < this.regexStr.length) {
      this.searchWithRegex();  
    } else {
      this.$articlesArr
        .css('display', 'block')
        .css('float', 'left')
    }
  },
  searchWithRegex : function () {
    var counter = 0;
    console.log('regex', this.regexStr);
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
    console.log('counter', counter);
  },
  showHits : function (pos) {
    //console.log('this.$articlesArr.get(pos)', this.$articlesArr.get(pos));
    $(this.$articlesArr.get(pos))
      .css('display', 'block')
      .css('float', 'left')
    ;
  }
};

var articles = Object.create(Articles);
articles.init();