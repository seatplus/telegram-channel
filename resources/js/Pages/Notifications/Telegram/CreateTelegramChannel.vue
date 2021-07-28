<template>
  <div
    :class="['text-gray-500 hover:text-gray-700','rounded-r-lg', 'group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10 cursor-pointer']"
    @click="open = !open"
  >
    <span>Create</span>
    <span
      aria-hidden="true"
      :class="['bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']"
    />
  </div>
  <teleport to="#destination">
    <ModalWithFooter v-model="open">
      <template #symbol>
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
          <svg
            class="h-6 w-6 text-green-600"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
            />
          </svg>
        </div>
      </template>
      <template #title>
        <span>Register a new Telegram Channel</span>
      </template>
      <template #description>
        <p class="text-sm text-gray-500">
          In order to send notifications to a channel, it must first be identified by the telegram bot. Please write <span class="text-gray-900 font-medium">/{{ randomString }}</span> in the channel you wish to register and click on the create button below.
          If the bot finds the said string, it will create a new tab.
        </p>
      </template>
      <template #buttons>
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
          <button
            type="button"
            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
            @click="submit"
          >
            Create
          </button>
        </span>
      </template>
    </ModalWithFooter>
  </teleport>
</template>

<script>
import {ref} from "vue";
import ModalWithFooter from "@/Shared/Modals/ModalWithFooter";
import {Inertia} from "@inertiajs/inertia";
import route from 'ziggy';

export default {
    name: "CreateTelegramChannel",
    components: {ModalWithFooter},
    emits: ['TelegramChannelCreated'],
    setup(props, {emit}) {
        const open = ref(false)
        const randomString = Math.random().toString(16).substr(2, 16);

        const submit = () => Inertia.post(route('telegram.register.channels'),{id: randomString}, {
            onSuccess: () => {
                open.value = false
                emit('TelegramChannelCreated')
            }
        })

        return {
            open,
            submit,
            randomString
        }
    }
}
</script>

<style scoped>

</style>