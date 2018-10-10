import Vue from './node_modules/vue/dist/vue.js'

new Vue({
	el: '#app',
	data: {
		mes: '',
		posts: []
	},
	methods: {
		getmes: function (event) {
			var val = event.target.value;
			if(val.length < 3) {
				this.posts = [];
				return;
			}
			fetch('api.php?action=gitHint&mes=' + val)
					.then((response) => {
						if (response.ok) {
							return response.json();
						}
						throw new Error('Network response was not ok');
					})
					.then((json) => {
						this.posts = json;
					})
					.catch((error) => {
						console.log(error);
					});
		},
		
		setmes: function(event) {
			this.mes = event.target.innerText
		}
	}
});

