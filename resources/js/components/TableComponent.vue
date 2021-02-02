<template>
    <v-card>
        <v-card-title>Films</v-card-title>
        <v-data-table
            :headers="headers"
            :items="data"
            :options="options"
            :server-items-length="total"
            :disable-sort="true"
            :loading="loading"
            class="elevation-1"
            @update:page="getDataFromApi"
            :footer-props="{
                itemsPerPageOptions:[]
            }"
        >
            <template
                v-for="header in headers.filter((header) =>
                  header.hasOwnProperty('formatter')
                )"
                v-slot:[`item.${header.value}`]="{ header, value }"
            >{{ header.formatter(value) }}</template>
        </v-data-table>
    </v-card>
</template>

<script>
export default {
    data () {
        return {
            loading: true,
            total: 0,
            data: [],
            options: {},
            pagination: {},
            headers: [
                {
                    text: 'Title',
                    align: 'start',
                    value: 'title',
                },
                { text: 'Opening crawl', value: 'opening_crawl'},
                { text: 'Director', value: 'director' },
                { text: 'Producer', value: 'producer' },
                { text: 'Release date', value: 'release_date' },
                { text: 'Created', value: 'created', formatter: this.formatDate },
                { text: 'Edited', value: 'edited', formatter: this.formatDate },
            ],
        }
    },
    mounted() {
        this.getDataFromApi();
    },
    methods: {
        formatDate (value) {
            let date = new Date(value);
            return date.toLocaleString();
        },
        getDataFromApi (page) {
            let url = '/api/films';

            if (page) {
                url = _.get(this.pagination, page + '.url')
            }

            this.loading = true;

            axios.get(url).then(response => {
                this.data = response.data.data;
                this.total = response.data.total;
                this.pagination = response.data.links;

                this.options = {
                    page: response.data.current_page,
                    itemsPerPage: response.data.per_page,
                };

                this.loading = false;
            });
        },
    },
}
</script>
