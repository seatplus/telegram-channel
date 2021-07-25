<template>
  <div class="space-y-3">
    <teleport to="#head">
      <title>{{ title(pageTitle) }}</title>
    </teleport>

    <PageHeader>{{ pageTitle }}</PageHeader>

    <div>
      <div class="sm:hidden">
        <label
          for="tabs"
          class="sr-only"
        >Select a channel</label>
        <select
          id="tabs"
          name="tabs"
          class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
        >
          <option
            v-for="channel in channels"
            :key="channel.name"
            :selected="channel.current"
            @click="visit(channel.route)"
          >
            {{ channel.name }}
          </option>
        </select>
      </div>
      <div class="hidden sm:block">
        <div class="border-b border-gray-200">
          <nav
            class="-mb-px flex space-x-6"
            aria-label="Tabs"
          >
            <inertia-link
              v-for="channel in channels"
              :key="channel.name"
              :href="$route(channel.route)"
              :class="[channel.current ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']"
              :aria-current="channel.current ? 'page' : undefined"
            >
              {{ channel.name }}
            </inertia-link>
          </nav>
        </div>
      </div>
    </div>

    <div v-if="!isSetup">
      Telegram is not setup yet. Have you created your bot yet?
    </div>

    <div
      v-else
      class="bg-white"
    >
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-8 md:grid-cols-6 lg:grid-cols-5">
          <NotificationComponent
            v-for="object in notifications"
            :key="object.title"
            :notification-object="object"
            :notifiable="notifiable"
          >
            <TelegramLogin v-show="!notifiable" />
          </NotificationComponent>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {Inertia} from "@inertiajs/inertia";

import PageHeader from "@/Shared/Layout/PageHeader";
import route from "ziggy";
import * as SolidHeroIcons from '@heroicons/vue/outline'
import NotificationComponent from "@/Shared/Notifications/Layout/NotificationComponent";
import TelegramLogin from "./Telegram/TelegramLogin";

export default {
    name: "TelegramIndex",
    components: {TelegramLogin, NotificationComponent, PageHeader, ...SolidHeroIcons},
    props: {
        channels: {
            required: true,
            type: Array
        },
        isSetup: {
            required: true,
            type: Boolean
        },
        notifiable: {
            required: false,
            type: Object,
            default: () => ({})
        },
        notifications: {
            required: true,
            type: Object
        }
    },
    setup() {

        function visit(route_name) {
            return Inertia.visit(route(route_name))
        }

        return {
            pageTitle: 'Notifications: Telegram',
            visit
        }
    }
}
</script>

<style scoped>

</style>