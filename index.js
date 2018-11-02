panel.plugin('KirbyCommerce', {
  'fields': {
    'kc-products-sync': {
      props: {
        message: String,
        username: String,
        sentence: String,
        progress: Number,
      },
      data: function () {
        return {
          message: this.message
        }
      },
    methods: {
      plus: function () {
        var self = this;
        var xhr = new XMLHttpRequest()
        xhr.open("GET", "/api/products", true)


        xhr.onprogress = function () {
            var array = this.responseText.split(/\r?\n/).filter(Boolean) ;
            var memoryLine = array[array.length - 2];
            var lastLineArray = array[array.length - 1].split(":");
                var trimmedArray = lastLineArray.map(function(string){
                    return string.trim();
                });


            self.$refs.progress.set(trimmedArray[0].replace("%", ""));
            self.message = trimmedArray[1];
        }
        xhr.send()



      }
    },
      template: `
        <div>
          
          <k-button v-on:click="plus">Add 1</k-button>
          <k-progress ref="progress" />
          <k-text>
            <i>{{message}}</i>
          </k-text>
       </div>
      `
    }
  }
});