<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-tabs fixed-tabs
                    dark
                    v-model="currentItem"
                    color="primary"
                    grow>
                <v-tab href="#newspaper">News Papers</v-tab>
                <v-tab href="#milk">Milk</v-tab>
            </v-tabs>
            <v-data-table
                    :headers="headers"
                    :items="subscriptions"
                    hide-actions
                    class="elevation-2">
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.name }}</td>
                    <td class="text-xs-center">{{ props.item.clientSubscription ? props.item.clientSubscription.quantity
                        : 'N/A' }}
                    </td>
                    <td class="text-xs-center">{{ props.item.clientSubscription ? props.item.clientSubscription.quantity
                        : 'N/A' }}
                    </td>
                    <td class="text-xs-center">{{ props.item.clientSubscription ? props.item.clientSubscription.quantity
                        : 'N/A' }}
                    </td>
                    <td class="text-xs-center">{{ props.item.clientSubscription ? props.item.clientSubscription.quantity
                        : 'N/A' }}
                    </td>
                    <td class="text-xs-center">
                        <v-btn small outline color="red"
                               v-if="props.item.clientSubscription">Unsubscribe
                        </v-btn>
                        <v-btn @click.native="subscribeItem = props.item" small outline color="primary" v-else>
                            Subscribe
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </v-flex>
        <v-flex xs12>
            <v-dialog v-model="subscribeItemDialog" max-width="500px">
                <v-card v-if="subscribeItem">
                    <v-card-title>
                        <span>Subscribe to <b>{{subscribeItem.name}}</b></span>
                    </v-card-title>
                    <v-card-text>
                        <v-text-field
                                v-model="subscribeItemQuantity"
                                required
                                label="Enter quantity"
                                placeholder="Quantity to be delivered to you">
                        </v-text-field>
                        <v-list subheader dense>
                            <v-subheader>Deliver everyday</v-subheader>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <v-list-tile-title>Everyday</v-list-tile-title>
                                    <v-list-tile-sub-title>Deliver everyday</v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-checkbox v-model="everydayCheckbox"></v-checkbox>
                                </v-list-tile-action>
                            </v-list-tile>
                            <v-divider class="mt-2"></v-divider>
                            <v-subheader>Deliver on specific day(s)</v-subheader>
                            <v-list-tile v-for="item in weekdays" :key="item.name" avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ item.name }}</v-list-tile-title>
                                    <v-list-tile-sub-title>{{ item.description }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-checkbox v-model="item.selected"></v-checkbox>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" flat @click.stop="subscribeItem = null">Cancel</v-btn>
                        <v-btn color="primary" @click.stop="subscribeItem = null">Continue</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-flex>
    </v-layout>
</template>

<script>
	export default {
		name: 'subscriptions',
		data () {
			return {
				connecting: false,
				subscribeItemDialog: false,
				currentItem: null,
				subscribeItem: null,
				subscribeItemQuantity: null,
				everydayCheckbox: false,
				subscriptions: [],
				headers: [
					{
						text: 'Name',
						align: 'left',
						sortable: false,
						value: 'name'
					},
					{text: 'Quantity Subscribed', sortable: false, value: ''},
					{text: 'Subscription Date', sortable: false, value: ''},
					{text: 'Total Deliveries', sortable: false, value: ''},
					{text: 'Total Cost', sortable: false, value: ''},
					{text: 'Subscription', sortable: false, value: ''},
				],
				weekdays: [
					{name: 'Monday', description: 'Deliver on monday', selected: false},
					{name: 'Tuesday ', description: 'Deliver on Tuesday', selected: false},
					{name: 'Wednesday', description: 'Deliver on Wednesday', selected: false},
					{name: 'Thursday', description: 'Deliver on Thursday', selected: false},
					{name: 'Friday', description: 'Deliver on Friday', selected: false}
				],
			}
		},
		watch: {
			currentItem (currentItem) {
				if (currentItem && !this.connecting) {
					this.loadSubscriptions()
				}
			},
			subscribeItem (subscribeItem) {
				this.subscribeItemDialog = !!subscribeItem
			},
			everydayCheckbox (everydayCheckbox) {
				if (everydayCheckbox) {
					for (let weekday of this.weekdays) {
						weekday.selected = false
					}
				}
			},
			weekdays (weekdays) {
				this.$utils.log(weekdays);
				let selected = 0
				for (let weekday of weekdays) {
					if (weekday.selected) {
						selected = selected + 1
					}
				}
				if (selected === 5) {
					this.everydayCheckbox = true
				}
			}
		},
		methods: {
			loadSubscriptions () {
				this.axios.get('/subscriptions', {
					params: {
						filter: this.currentItem,
					}
				}).then(response => {
					if (response.data) {
						this.subscriptions = []
						for (let item of response.data.data) {
							this.subscriptions.push(item)
						}
					}
				}).catch(error => {

				})
			}
		},
		mounted () {
			this.currentItem = 'newspaper'
		}
	}
</script>

<style scoped>

</style>