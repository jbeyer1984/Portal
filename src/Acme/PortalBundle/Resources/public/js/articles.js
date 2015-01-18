var self;

Articles = {
  regexStr: '',
  regexContainer : {},
  articlesStrArr: [],
  articlesArr: {},
  init : function () {
    self = this;
    $(document).ready( function () {
      self.applyHandlers();
    });
  },
  applyHandlers : function () {
    this.applyRegexHandler();
  },
  applyRegexHandler : function () {
    this.articlesArr = $('.article');
    this.regexContainer = $('.regex');
    this.articlesArr.each( function() {
      self.articlesStrArr.push($(this).find('input').eq(1).val());
    });
    console.log('this.articlesStrArr', this.articlesStrArr);
    $('.regex .update').click( function (e) {
      self.handleRegex(e);
    });
  },
  handleRegex : function (e) {
    this.regexStr = this.regexContainer.find('input').eq(0).val();
    if (2 < this.regexStr.length) {
      this.searchWithRegex();  
    }
  },
  searchWithRegex : function () {
    var counter = 0;
    console.log('regex', regex);
    this.articlesArr
      .css('display', 'none')
      .css('float', 'none')
    ;
    for (var pos = 0; pos < this.articlesStrArr.length; pos++) {
      var str = this.articlesStrArr[pos];
      var regex = new RegExp(this.regexStr, 'gi');
      if (true == regex.test(str)) {
        this.showHits(pos);
      }
    }
    console.log('counter', counter);
  },
  showHits : function (pos) {
    //console.log('this.articlesArr.get(pos)', this.articlesArr.get(pos));
    $(this.articlesArr.get(pos))
      .css('display', 'block')
      .css('float', 'left')
    ;
  }
};

var articles = Object.create(Articles);
articles.init();