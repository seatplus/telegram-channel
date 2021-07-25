<template>
  <div ref="telegram" />
</template>

<script>
import route from 'ziggy'
import {onBeforeMount, ref} from "vue";

export default {
    name: "TelegramLogin",
    setup() {

        const telegram = ref(null)

        let bot_name;
        let redirect_url;

        const buildTelegramLogin = async () => {

            await axios.get(route('telegram.auth.infos'))
                .then(response => {
                    bot_name = response.data.botname
                    redirect_url = response.data.callback_url
                }).catch(error => console.log(error))

            const script = document.createElement('script')
            script.async = true
            script.src = 'https://telegram.org/js/telegram-widget.js?15'
            script.setAttribute('data-size', 'large')
            script.setAttribute('data-userpic', 'false')
            script.setAttribute('data-telegram-login', bot_name)
            script.setAttribute('data-request-access', 'write')
            //if (this.radius) { script.setAttribute('data-radius', this.radius) }
            script.setAttribute('data-auth-url', redirect_url)

            telegram.value.appendChild(script)
        }

        onBeforeMount(() => {
            buildTelegramLogin()
        })

        return {
            telegram
        }
    }
}
</script>

<style scoped>

</style>