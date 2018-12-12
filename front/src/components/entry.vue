<template>
	<div id="entry">
		<modal name="infoview" height="auto" :width=800>
			<div v-if="active">
				<h1>restaurant info</h1>
				<div class="col-md-12">
					<div class="col-md-4">
						<br/>
						<img :src="active.image" alt="">
					</div>

					<div class="col-md-8">
						<h2>{{ active.name }} | {{ active.rating }} ({{ active.review_count }}) | {{ active.price_range }}</h2>
						<h3>{{ active.address }}, {{ active.zip }}, {{ active.state }}</h3>
						<p class="f2 hover-pink dib mr4"><a :href="active.menu_url">Menu</a></p>
						<p class="f2 hover-pink dib mr4"><a :href="active.url">Yelp</a></p>
						<p class="f2 dib">Zomato Delivery: {{ active.has_online_delivery }}</p>
					</div>
					<div class="col-md-12 text-center">
							<br>
							<h2>Suggested Recipies</h2>
							<div v-if="recipies">
								<div v-for="rec in recipies">
									<h3><a :href="rec.uri">{{ rec.label }}</a>: Cook Time - {{ rec.totalTime }} minutes {{ rec.calories }} calories</h3>
									<h4 @click="trigger(rec.ingredientLines)">Ingredients</h4>
									<alert ref="alert"></alert>
									<h4 v-for="about in JSON.parse(rec.healthLabel)" class="dib mr4">
										{{ about }}
									</h4>

								</div>
							</div>

						</div>					
					</div>
			</div>
		</modal>

		<div class="col-md-4">
			<h1 class="text-center">Glassboro, NJ Restaurants</h1>
			<br/>
			<div v-for="res in test" class="">
				<h3 class="hover-purple" @click="info(res)">{{ res.name }}</h3>
				<p class="f3 dib">{{ res.address }} • {{ res.rating }} ({{ res.review_count }}) • {{ res.price_range }} • {{ res.cuisines }}</p>
				<div class="col-md-12">
				<p class="f4 hover-pink dib mr4"><a :href="res.menu_url">Menu</a></p>
				<p class="f4 hover-pink dib mr4"><a :href="res.url">Yelp</a></p>
				<p class="f4 dib">Zomato Delivery: {{ res.has_online_delivery }}</p>
				</div>	


			</div>			
		</div>

		<div id="mapcontainer" class="col-md-8">
			<div id="map">
				
			</div>
		</div>
		
	</div>
</template>

<script>
	var url = 'http://localhost:3000'
	export default {
		data () {
			return {
				test: '',
				map: '',
				tileLayer: '',
				active: '',
				recipies: ''
			}
		},

		mounted(){
			this.initMap()
		},

		methods: {
			initMap(){
				this.map = L.map('map').setView([39.54, -74.08], 9)

				this.tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
			    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			    maxZoom: 18,
			    id: 'mapbox.streets',
			    accessToken: 'pk.eyJ1IjoiaXJpc2hyNiIsImEiOiJjam5ueXdneXgwNG51M3JrMHVvajZ4bWk3In0.AvgU2HCd06zZgOW61UOUrQ'
				})

				this.tileLayer.addTo(this.map)				
			},

			addPoints(){
				for(var rest of this.test){
					var jitter = Math.random() * (0.001 - 0.005) + 0.001					
					let master = [rest.lat, rest.lng + jitter]
					//let master = [rest.lat, rest.lng]
					let marker = L.marker(master).addTo(this.map)
					marker.bindPopup(rest.name)
					this.map.flyTo(master, 15)
				}							
			},

			info(active){
				this.seedRecipies(active.cuisines)
				this.$modal.show('infoview')
				this.active = active
				let master = [active.lat, active.lng]
				this.map.flyTo(master, 18)
			},

			seedRecipies(type){
				const pull = url + '/recps'
				const body = {'type': type}
				this.axios.post(pull, body).then((response) => {
					this.recipies = response.data
				})
			},

			trigger(lines){
				var line = JSON.parse(lines)
				alert(line)
			}


		},		

		created() {
			const pull = url + '/bus'
			this.axios.get(pull).then((response) => {
			  this.test = response.data
			  this.addPoints()
			})
		}


	}
</script>

<style>
	#map {
		height: 1000px;
	}	
</style>