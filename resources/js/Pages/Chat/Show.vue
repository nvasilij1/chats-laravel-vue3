<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from "@inertiajs/vue3";
</script>
<template>
    <Head title="Сообщения" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ chat.title ?? 'Ваш чат' }}</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-start chat-wrap">
                    <div class="w-3/4 p-4 mr-4 bg-white border border-gray-200 chat-div">
                        <div ref="message_box" class="mb-4" v-if="messages">
                            <div v-if="!isLastPage" class="text-center mb-2">
                                <a @click.prevent="getMessages"
                                   class="inline-block bg-sky-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Загрузить ещё</a>
                            </div>
                            <div v-for="message in messages.slice().reverse()" :class="message.is_owner ? ' text-right': ''">
                                <div :class="['p-4 mb-4 border inline-block rounded-lg border',
                                message.is_owner ? 'bg-green-50 border-green-100': 'bg-sky-50 border-sky-100']">
                                    <p class="text-sm">{{ message.user_name }}</p>
                                    <p class="mb-2">{{ message.body }}</p>
                                    <p class="text-xs italic">{{ message.time }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-col w-full chat-hidden"></div>
                            <div  class="flex-col justify-end">
                                <h3 class="text-gray-700 mb-4 text-lg">Отправить сообщение</h3>
                                <div>
                                    <div class="mb-4">
                                        <input placeholder="сообщение" class="rounded-lg border border-gray-400" type="text"
                                               v-model="body">
                                    </div>
                                    <div>
                                        <a @click.prevent="store"
                                           class="inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Отправить</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/4 p-4 bg-white border border-gray-200  chat-div">
                        <h3 class="text-gray-700 mb-4 text-lg">Пользовалети</h3>
                        <div v-if="users">
                            <div v-for="user in users" class="flex items-center pb-4 mb-4 border-b border-gray-300">
                                <p class="mr-2">{{ user.id }}</p>
                                <p class="mr-4">{{ user.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>

export default {
    name: "Show",

    props: [
        'chat',
        'users',
        'messages',
        'isLastPage'
    ],

    created() {
        window.Echo.channel(`store-message.${this.chat.id}`)
            .listen('.store-message', res => {
                this.messages.unshift(res.message)
                if (this.$page.url === `/chats/${this.chat.id}`
                ) {
                    axios.patch('/message_statuses', {
                        message_id: res.message.id,
                        user_id: this.$page.props.auth.user.id
                    })
                    .catch( error => {
                        console.log(error)
                    });
                }
            })
    },

    data() {
        return {
            body: '',
            page: 1,
        }
    },

    computed: {
        userIds() {
            return this.users.map(user => {
                return user.id
            }).filter(userId => {
                return userId !== this.$page.props.auth.user.id
            })
        },
    },


    methods: {
        store() {
            axios.post('/messages', {
                chat_id: this.chat.id,
                body: this.body,
                user_ids: this.userIds
            })
                .then(res => {
                    this.messages.unshift(res.data)
                    this.body = ''
                })
        },

        getMessages() {
            axios.get(`/chats/${this.chat.id}?page=${++this.page}`)
                .then(res => {
                    this.messages.push(...res.data.messages)
                    this.$page.props.isLastPage = res.data.is_last_page
                })
        },
    }

}
</script>

<style scoped>
@media (max-width: 800px) {
    .chat-wrap {
        flex-wrap: wrap-reverse;
    }
    .chat-div {
        width: 100%;
        margin-bottom: 10px;
    }
    .chat-hidden {
        display: none;
    }
}
</style>
