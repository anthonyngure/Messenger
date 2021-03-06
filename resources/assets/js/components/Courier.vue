<template>
    <v-layout row wrap>
        <v-flex xs12 class="pt-3">
            <v-card tile flat>
                <v-toolbar color="primary" flat dense dark tabs>
                    <v-toolbar-side-icon></v-toolbar-side-icon>
                    <v-toolbar-title>Deliveries</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn flat @click.native="addingDelivery = true">
                            <v-icon class="mr-3">add</v-icon>
                            Add a Delivery
                        </v-btn>
                    </v-toolbar-items>
                    <v-menu :nudge-width="100">
                        <v-toolbar-title slot="activator">
                            <span>{{month}}</span>
                            <v-icon dark>arrow_drop_down</v-icon>
                        </v-toolbar-title>
                        <v-date-picker type="month"
                                       color="green lighten-1"
                                       header-color="primary"
                                       :min="minMonth"
                                       :max="maxMonth"
                                       v-model="month">
                        </v-date-picker>
                    </v-menu>
                    <v-btn icon @click="addingDelivery = true">
                        <v-icon>add</v-icon>
                    </v-btn>
                    <v-btn icon @click="loadDeliveries">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                    <v-menu offset-y>
                        <v-btn icon slot="activator">
                            <v-icon>more_vert</v-icon>
                        </v-btn>
                        <v-list>
                            <v-list-tile @click="loadDeliveries">Refresh</v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-tabs
                            fixed-tabs
                            v-model="currentItem"
                            color="transparent"
                            slider-color="yellow"
                            grow
                            slot="extension">
                        <v-tab href="#pending">Pending Deliveries</v-tab>
                        <v-tab href="#complete">Complete Deliveries</v-tab>
                    </v-tabs>
                </v-toolbar>
                <v-card-text>
                    <v-layout v-if="connecting" row justify-center align-center>
                        <v-progress-circular v-if="connecting" indeterminate v-bind:size="36" color="primary">
                        </v-progress-circular>
                        <v-subheader v-if="connecting" class="mt-2">Updating...</v-subheader>
                        <p v-if="!connecting && !items.length">Nothing found...</p>
                    </v-layout>
                    <v-layout v-else row wrap>
                        <v-flex xs12>
                            <v-data-iterator
                                    content-tag="v-expansion-panel"
                                    :items="items"
                                    popout
                                    :rows-per-page-items="rowsPerPageItems"
                                    :pagination.sync="pagination">
                                <v-expansion-panel-content slot="item"
                                                           slot-scope="props"
                                                           lazy
                                                           :value="items.length === 1">
                                    <!--Header-->
                                    <div slot="header">
                                        <v-list-tile-content>
                                            <v-list-tile-title class="subheading">
                                                <b>From: {{ props.item.originName }}</b>-
                                                <timeago class="accent--text" :since=" props.item.createdAt"></timeago>
                                            </v-list-tile-title>
                                            <v-list-tile-sub-title class="caption primary--text">
                                                {{ props.item.originFormattedAddress }}
                                            </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-chip color="accent" text-color="white">
                                            <b>{{$utils.formatMoney(props.item.estimatedCost)}}</b>
                                        </v-chip>
                                        <v-chip v-for="(stat,i) in props.item.stats" :key="i"
                                                small color="primary" text-color="white">
                                            <v-avatar :size="24">
                                                <img style="max-height: 24px; max-width: 24px"
                                                     :src="'/storage/'+stat.courierOption.icon"
                                                     :alt="stat.courierOption.name">
                                            </v-avatar>
                                            <b>{{stat.count}} </b>{{stat.courierOption.name+ (stat.count === 1 ? '':
                                            's')}}
                                        </v-chip>
                                    </div>
                                    <!--Content-->
                                    <v-list three-line>
                                        <v-divider :inset="false"></v-divider>
                                        <template v-for="(item,index) in props.item.items">
                                            <v-list-tile avatar :key="index">
                                                <v-list-tile-avatar>
                                                    <img :src="'/storage/'+item.courierOption.icon">
                                                </v-list-tile-avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title class="body-2">
                                                        <b>{{item.quantity}} -
                                                            {{item.courierOption.name+ (item.quantity === 1 ? '': 's')}}
                                                        </b>
                                                        - {{item.status}}
                                                    </v-list-tile-title>
                                                    <v-list-tile-sub-title class="caption primary--text">
                                                        <b>To: </b>{{item.destinationName}}
                                                    </v-list-tile-sub-title>
                                                    <v-list-tile-sub-title class="caption accent--text">
                                                        {{item.destinationFormattedAddress}}
                                                    </v-list-tile-sub-title>
                                                </v-list-tile-content>
                                                <v-list-tile-action v-if="item.status === 'AT_PICKUP'">
                                                    <v-btn slot="activator" @click="printingItem = item"
                                                           flat outline color="primary">
                                                        Print QR
                                                    </v-btn>
                                                </v-list-tile-action>
                                                <v-list-tile-action v-if="item.status !=='AT_PICKUP'">
                                                    <v-btn slot="activator" @click="trackingItem = item"
                                                           flat outline color="primary">
                                                        Track
                                                    </v-btn>
                                                </v-list-tile-action>
                                            </v-list-tile>
                                            <v-divider inset v-if="index !== props.item.items.length-1"
                                                       :key="index+props.item.items.length"></v-divider>
                                        </template>
                                    </v-list>
                                </v-expansion-panel-content>
                            </v-data-iterator>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
        <v-flex xs12>
            <v-dialog
                    v-model="addingDelivery"
                    fullscreen
                    transition="dialog-bottom-transition"
                    :overlay="false"
                    lazy>
                <delivery-form @onClose="onCloseAddingDelivery">
                </delivery-form>
            </v-dialog>
            <v-dialog
                    v-model="trackingItem"
                    fullscreen
                    transition="dialog-bottom-transition"
                    :overlay="false"
                    scrollable>
                <tracking-dialog-content @onClose="trackingItem = null"
                                         :item="trackingItem">
                </tracking-dialog-content>
            </v-dialog>
            <delivery-item-q-r-dialog :item="printingItem" @onClose="printingItem = null"></delivery-item-q-r-dialog>
        </v-flex>

    </v-layout>
</template>

<script>
    import PickPackMap from './PickPackMap'
    import Base from './Base.vue'
    import EventBus from '../event-bus'
    import DeliveryForm from './DeliveryForm'
    import moment from 'moment'
    import TrackingDialogContent from './TrackingDialogContent'
    import DeliveryItemQRDialog from './DeliveryItemQRDialog'

    export default {
        extends: Base,
        components: {
            DeliveryItemQRDialog,
            TrackingDialogContent,
            DeliveryForm,
            PickPackMap
        },
        name: 'courier',
        data () {
            return {
                addingDelivery: false,
                items: [],
                connecting: false,
                trackingItem: null,
                printingItem: null,
                month: null,
                minMonth: null,
                maxMonth: null,
                rowsPerPageItems: [2, 4, 8],
                pagination: {
                    rowsPerPage: 4
                },
                currentItem: null,
            }
        },
        watch: {
            month (month) {
                if (month && this.currentItem && !this.connecting) {
                    this.loadDeliveries()
                }
            },
            currentItem (currentItem) {
                if (this.month && currentItem && !this.connecting) {
                    this.loadDeliveries()
                }
            }
        },
        methods: {
            onCloseAddingDelivery (refresh) {
                this.addingDelivery = false
                if (refresh) {
                    this.loadDeliveries()
                }
            },
            loadDeliveries () {
                this.connecting = true
                let that = this
                this.axios.get('/deliveries', {
                    params: {
                        filter: this.currentItem,
                        month: this.month,
                    }
                }).then(response => {
                    let deliveries = response.data.data
                    that.items = []
                    for (let delivery of deliveries) {
                        that.items.push(delivery)
                    }
                    that.connecting = false
                }).catch(e => {
                    //console.error('Error ' + e);
                    that.connecting = false
                })
            },
        },
        mounted () {
            let dateClientJoined = moment(this.$auth.user().client.createdAt)
            this.minMonth = dateClientJoined.year() + '-' + (dateClientJoined.month())
            let today = moment()
            this.maxMonth = today.year() + '-' + (today.month() + 2)
            this.month = today.year() + '-' + today.month()
            this.currentItem = 'pending'
            let that = this
            EventBus.$on('refreshDeliveryHistoryList', function () {
                that.loadDeliveries()
            })
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
