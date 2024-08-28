<template>
<Modal id="new-ticket" ref="modal">
    <template #header>
        <h2>Create new ticket</h2>
        <div v-if="status === 'error'" class="alert badge alert_red">{{ message }}</div>
    </template>

    <p v-if="status === 'success'">
        <img src="/img/icons/icon_check.svg" class="img_success"/>
        <span>  {{ message }}</span>
    </p>

    <div v-if="status !== 'success'">
        <input v-model="subject" placeholder="Subject" class="form_input">

        <textarea v-model="body" placeholder="Ticket text" class="form_input margin_top_10 ticket-text"></textarea>

        <button
            v-if="!image"
            class="btn margin_top_10"
            @click="onPickFile"
            style="margin-left: 0px"
        >
            Add image
        </button>

        <div v-else class="margin_top_10">
            <p>Selected file:</p>
            <span>
                {{this.image.name}}
                <button type="button" @click="image = null" class="remove" ></button>
            </span>
        </div>

        <input
            type="file"
            style="display: none"
            ref="fileInput"
            accept="image/*"
            @change="onFilePicked"/>

        <div class="popup_end">
            <div class="popup_row">

            </div>
        </div>
    </div>

    <template #footer>
        <button type="button" @click="hide" class="btn btn-w50 btn_white" style="margin-left: 0px">Close</button>
        <button v-if="status !== 'success'" type="button" @click="createTicket" class="btn btn-w50">Create</button>
    </template>
</Modal>
</template>

<script>
import Modal from "./Modal.vue";
import SupportService from "../../services/api/SupportService.js";
import PictureInput from 'vue-picture-input'

export default {
    name: "NewTicketModal",
    components: {Modal, PictureInput},
    props: {},
    data() {
        return {
            status: null,
            message: null,
            subject: '',
            body: '',
            image: null,
        }
    },
    methods: {
        show(proxy) {
            this.init();
            this.proxy = proxy;
            this.$refs.modal.show();
        },
        hide() {
            this.$refs.modal.hide();
        },
        async createTicket() {
            await (new SupportService)
                .createTicket(this.subject, this.body, this.image)
                .then((response) => {
                    this.status = 'success';
                    this.message = 'Ticket created successfully';
                    this.$emit('created');
                    this.hide();
                })
                .catch((error) => {
                    console.log(error.response.data)
                    this.status = 'error';
                    this.message = error.response.data.message;
                });
        },
        onPickFile () {
            this.$refs.fileInput.click()
        },
        onFilePicked (event) {
            const files = event.target.files
            let filename = files[0].name
            const fileReader = new FileReader()
            fileReader.addEventListener('load', () => {
                this.imageUrl = fileReader.result
            })
            fileReader.readAsDataURL(files[0])
            this.image = files[0]
            console.log(this.image);
        },
        init() {
            this.status = null;
            this.message = null;
            this.subject = '';
            this.body = '';
            this.image = null;
        }
    }
}
</script>

<style scoped>
.mr-6 {
    margin-right: 6px;
}
.ticket-text {
    height: 120px !important;
}
.btn-w50
{
    width: 50%;
}
.remove {
    margin-top: 5px;
    padding: 5px;
    width: 20px;
    height: 20px;
    background: url('../../../images/icons/icon_remove.svg') 50% no-repeat;
    background-size: 18px;
    border-radius: 50%;
}
.form_input {
    width: 100%;
    height: 45px;
    border-radius: 8px;
    border: 1px solid #4470FE;
    background: white;
    padding: 14px;
    box-sizing: border-box;
}
.margin_top_10 {
    margin-top: 10px !important;
}
</style>
