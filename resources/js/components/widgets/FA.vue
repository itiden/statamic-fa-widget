<template>
  <div>
    <div
      class="flex flex-col md:flex-row justify-between items-start md:items-center p-4"
    >
      <div>
        <h2 class="flex items-end justify-end mb-2 md:m-0">
          <div class="h-6 w-6 mr-2 text-gray-800">
            <svg-icon name="telescope" class="text-gray-700 h-5 w-5" />
          </div>
          <span>Analytics</span>
        </h2>
        <a class="ml-8" href="https://www.usefathom.com" target="blank">
          <img src="./fa.png" alt="fathom analytics badge" width="180px" />
        </a>
      </div>
      <div class="flex flex-row-reverse items-center">
        <div class="flex shadow rounded">
          <button
            class="text-sm whitespace-nowrap bg-white flex text-gray-800 border-r p-2 h-8 rounded-l items-center justify-center hover:bg-gray-100 hover:text-gray-800"
            :class="{ current: activeFilters.interval === 'all' }"
            @click="activeFilters = { interval: 'all' }"
          >
            All
          </button>
          <button
            class="text-sm whitespace-nowrap bg-white flex text-gray-800 border-r p-2 h-8 items-center justify-center hover:bg-gray-100 hover:text-gray-800"
            :class="{ current: activeFilters.interval === 'month' }"
            @click="activeFilters = { interval: 'month' }"
          >
            Last Month
          </button>
          <button
            class="text-sm whitespace-nowrap bg-white flex text-gray-800 border-r p-2 h-8 items-center justify-center hover:bg-gray-100 hover:text-gray-800"
            :class="{ current: activeFilters.interval === 'week' }"
            @click="activeFilters = { interval: 'week' }"
          >
            Last Week
          </button>
          <button
            class="text-sm whitespace-nowrap bg-white flex text-gray-800 border-r p-2 h-8 rounded-r items-center justify-center hover:bg-gray-100 hover:text-gray-800"
            :class="{ current: activeFilters.interval === 'today' }"
            @click="activeFilters = { interval: 'today' }"
          >
            Today
          </button>
        </div>
      </div>
    </div>

    <div v-if="initializing" class="loading">
      <loading-graphic />
    </div>
    <data-list
      v-if="!initializing && items.length"
      :rows="items"
      :columns="columns"
      :visible-columns="visibleColumns"
      :search-query="searchQuery"
      :sort="false"
      :sort-column="sortColumn"
      :sort-direction="sortDirection"
      initial-sort-column="pageviews"
      initial-sort-direction="desc"
    >
      <div slot-scope="{}">
        <data-list-table rows="10" @sorted="sorted" v-show="items.length">
          <template slot="cell-pathname" slot-scope="{ row: item }">
            <div class="text-end">
              <a :href="`${item.hostname}${item.pathname}`" target="blank">{{
                item.pathname
              }}</a>
            </div>
          </template>
          <template slot="actions" slot-scope="{ row: item }">
            <dropdown-list>
              <dropdown-item
                :text="`${__('Edit')}${
                  item.edit_url === null ? ' (not found)' : ''
                }`"
                :redirect="item.edit_url"
              />
            </dropdown-list>
          </template>
        </data-list-table>
        <data-list-pagination
          class="px-4 py-3 border-t bg-gray-200 rounded-b-lg text-sm"
          :resource-meta="meta"
          :per-page="perPage"
          :show-totals="true"
          @page-selected="selectPage"
          @per-page-changed="changePerPage"
        />
      </div>
    </data-list>
    <p
      v-else-if="!initializing && !items.length"
      class="p-4 pt-2 text-sm text-gray-500"
    >
      {{ __('No analytics found') }}
    </p>
  </div>
</template>

<style scoped>
.current {
  background-color: rgb(250 252 255) !important;
  --tw-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05);
  --tw-shadow-colored: inset 0 2px 4px 0 var(--tw-shadow-color);
  box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000),
    var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
  color: rgb(67 169 255) !important;
}
</style>

<script>
import Listing from '../../../../vendor/statamic/cms/resources/js/components/Listing.vue';
export default {
  mixins: [Listing],

  props: {
    siteId: String,
  },

  data() {
    return {
      listingKey: 'items',
      requestUrl: cp_url(`fa`),
      perPage: 25,
      activeFilters: {
        interval: 'all',
      },
      sortColumn: 'pageviews',
      sortDirection: 'desc',
    };
  },
};
</script>
