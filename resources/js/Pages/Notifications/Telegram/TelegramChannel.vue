<template>
  <div :key="channel_results.length">
    <div class="sm:hidden">
      <label
        for="tabs"
        class="sr-only"
      >Select a tab</label>
      <select
        id="tabs"
        name="tabs"
        class="block w-full focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
      >
        <option
          v-for="tab in tabs"
          :key="tab.name"
          :selected="tab.current"
        >
          {{ tab.name }}
        </option>
      </select>
    </div>
    <div class="hidden sm:block">
      <nav
        class="relative z-0 rounded-lg shadow flex divide-x divide-gray-200"
        aria-label="Tabs"
      >
        <div
          v-for="(tab, tabIdx) in channels"
          :key="tab.name"
          :class="[tab.current ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700', tabIdx === 0 ? 'rounded-l-lg' : '', 'group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10 cursor-pointer']"
          :aria-current="tab.current ? 'page' : undefined"
          @click="changeNotifiable(tab)"
        >
          <span>{{ tab.name }}</span>
          <span
            aria-hidden="true"
            :class="[tab.current ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']"
          />
        </div>
        <CreateTelegramChannel @TelegramChannelCreated="fetchChannels()" />
      </nav>
    </div>
  </div>
</template>

<script>
import {computed, onBeforeMount, onMounted, onUpdated, ref, watch} from "vue";
import route from 'ziggy';
import CreateTelegramChannel from "./CreateTelegramChannel";

const tabs = [
    { name: 'Private Notification', object: '#', current: true },
    { name: 'Company', href: '#', current: false },
    { name: 'Team Members', href: '#', current: false },
    { name: 'Billing', href: '#', current: false },
]

export default {
    name: "TelegramChannel",
    components: {CreateTelegramChannel},
    props: {
        modelValue: Object
    },
emits: ['update:modelValue'],
    setup(props, {emit}) {

        let channel_results = [];
        const channels = ref([])

        const fetchChannels = async () => {
            await axios.get(route('telegram.get.channels'))
                .then(result => {
                    channel_results =result.data
                    mapChannels()
                })
                .catch(error => console.log(error))
        }
        const mapChannels = () => channels.value = _.chain(channel_results)
            .filter()
            .map(channel => (
              {
                  ...channel,
                  current: _.isEqual(_.get(channel, 'notifiable'), props.modelValue)
              }
            ))
            .value()

        const changeNotifiable = (channel) => emit('update:modelValue', channel.notifiable)

        watch(() => props.modelValue, () => mapChannels())

        onMounted(() => {
            fetchChannels()
        })

        return {
            tabs,
            channel_results,
            channels,
            fetchChannels,
            changeNotifiable
        }
    },
}
</script>

<style scoped>

</style>