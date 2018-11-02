panel.plugin('KirbyCommerce', {
  'fields': {
    'kc-products-sync': {
      props: {
        message: String,
        progress: Number,
        disabled: Boolean
      },
      data: function () {
        return {
          message: this.message,
          disabled: this.disabled,
        }
      },
    methods: {
      sync: function () {
        var self = this;
        self.disabled = true;
        var xhr = new XMLHttpRequest()
        xhr.open("GET", "/api/products", true)


        xhr.onprogress = function () {
            var array = this.responseText.split(/\r?\n/).filter(Boolean) ;
            var lastLineArray = array[array.length - 1].split(":");
            var trimmedArray = lastLineArray.map(function(string){
                return string.trim();
            });
            var progress = trimmedArray[0].replace("%", "");
            var message = trimmedArray[1];


            self.$refs.progress.set(progress);

            self.message = message;
            self.disabled = Boolean(progress) ? true : false;
            if(progress == 100) {
              setTimeout(function(){ 
                self.$refs.progress.set(0); 
                self.disabled = false;
              }, 3000);
            }
        }
        xhr.send()



      }
    },
      template: `
        <div>
          <k-button @click="sync" :disabled="disabled" icon="refresh">Sync All Products</k-button>
          <k-progress ref="progress" />
          <k-text v-if="disabled">
            <i>{{message}}</i>
          </k-text>
        </div>
      `
    }
  }
});