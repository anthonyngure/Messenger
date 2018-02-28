<template>
    <v-layout row wrap style="margin: 0">
        <v-flex xs12>
            <v-tabs fixed-tabs
                    dark
                    v-model="currentItem"
                    color="primary"
                    grow>
                <v-tab>News Papers</v-tab>
                <v-tab>Milk</v-tab>
            </v-tabs>
        </v-flex>
    </v-layout>
</template>

<script>
	export default {
		name: 'subscriptions',
		data () {
			return {
				currentItem: null,
			}
		}
	}
</script>

<style scoped>

</style>