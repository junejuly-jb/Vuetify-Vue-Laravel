<template>
    <v-app>
        <v-container>
                <v-card>
                    <v-card-title>
                        <div class="headline">Admin Lists</div>
                        
                    </v-card-title>
                    <div class="container">
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Search"
                            single-line
                            hide-details
                            clearable
                        ></v-text-field>
                    </div>
                    <v-btn
                        class="ma-2"
                        color="info"
                        @click="addUser = true"
                        >
                        <v-icon>mdi-account-multiple-plus</v-icon> Add admin
                        
                    </v-btn>
                    <v-btn
                        class="ma-2"
                        :loading="loading4"
                        :disabled="loading4"
                        color="info"
                        @click="loader = 'loading4'"
                        >
                        <v-icon>mdi-refresh</v-icon> Refresh
                        <template v-slot:loader>
                            <span class="custom-loader">
                            <v-icon light>mdi-cached</v-icon>
                            </span>
                        </template>
                    </v-btn>
                    <div class="float-right">
                        Float right on all viewport sizes
                    </div>
                    <v-data-table
                    :headers="headers"
                    :items="users"
                    :search="search"
                    >
                        <template v-slot:item.actions="{ item }">
                            <v-icon @click="editUser(item)" color="success">mdi-pencil</v-icon>
                            <v-icon @click.prevent="deleteUser(item)" color="error">mdi-delete</v-icon>
                        </template>
                    </v-data-table>
                </v-card>


                <v-dialog
                v-model="testDialog"
                persistent
                max-width="350"
                >
                    <v-card>
                        <v-card-title class="headline">
                        Delete this item?
                        </v-card-title>
                        <v-card-text></v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green darken-1"
                            text
                            @click="testDialog"
                        >
                            Disagree
                        </v-btn>
                        <v-btn
                            color="green darken-1"
                            text
                            @click="deleteItemConfirm"
                        >
                            Agree
                        </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog
                v-model="addUser"
                persistent
                max-width="500"
                >
                    <v-card>
                        <v-card-title class="headline">
                        Add admininistrator
                        </v-card-title>
                        <v-card-text>Please make <small class="text-danger">" letmein "</small> as a default password for new admins!</v-card-text>
                        <v-container>
                            <v-form>
                            <v-text-field
                                v-model="form.name"
                                :rules="rules.name"
                                label="Enter name"
                                clearable
                            ></v-text-field>
                            <v-text-field
                                v-model="form.email"
                                :rules="emailRules"
                                label="Enter Email Address"
                                hint="Email must contains '@'"
                                clearable
                            ></v-text-field>
                            <v-text-field
                                v-model="form.password"
                                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                :rules="[rules.required, rules.min]"
                                :type="show1 ? 'text' : 'password'"
                                name="input-10-1"
                                label="Enter Password"
                                hint="At least 8 characters"
                                counter
                                @click:append="show1 = !show1"
                            ></v-text-field>
                            </v-form>
                        </v-container>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="error"
                            text
                            @click="closeAddModal"
                        >
                            Cancel
                        </v-btn>
                        <v-btn
                            color="info"
                            text
                            @click="saveUser"
                        >
                            Save
                        </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
        </v-container>
    </v-app>
</template>
<script>
  export default {
    data () {
      return {
        search: '',
        headers: [
          {
            text: 'ID Number',
            align: 'start',
            value: 'id',
          },
          { text: 'Name', value: 'name' },
          { text: 'Email', value: 'email' },
          { text: 'Action', value: 'actions', sortable: false},
        ],
        users: [],
        loader: null,
        loading4: false,
        testDialog: false,
        addUser: false,
        editedIndex: -1,
        selected_user: '',
        form: {
            name: '',
            email: '',
            password: ''
        },
        rules: {
            name: [val => (val || '').length > 0 || 'This field is required'],
            required: value => !!value || 'Required.',
            min: v => v.length >= 6 || 'Min 6 characters',
        },
        show1: false,
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+/.test(v) || 'E-mail must be valid',
        ],
      }
    },
    methods: {
        async getAllUsers(){
            await axios.get('api/users')
            .then((res) => {
                this.users = res.data
            })
        },
        async saveUser(){
            await axios.post('api/addUser', this.form)
            .then((res) => {
                this.addUser = false
                this.formReset()
            })
        },
        deleteUser(item){
            this.editedIndex = this.users.indexOf(item)
            this.selected_user = item.id
            console.log(this.editedIndex, this.selected_user)
            this.testDialog = true
        },
        async deleteItemConfirm () {
            await axios.delete('api/delete/' + this.selected_user)
            .then((res) => {
                this.users.splice(this.editedIndex, 1)
                console.log(res.data.message)
                this.testDialog = false
            })
        },
        formReset(){
            this.form.name = '';
            this.form.email = '';
            this.form.password = '';
        },
        closeAddModal(){
            this.addUser = false;
            this.formReset()
        }
    },
    created(){
        this.getAllUsers()
    },
    watch: {
      loader () {
        this.getAllUsers()
        const l = this.loader
        this[l] = !this[l]

        setTimeout(() => (this[l] = false), 2000)
        this.loader = null
      },
    },
  }
</script>
<style scoped>
  .custom-loader {
    animation: loader 1s infinite;
    display: flex;
  }
  @-moz-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @-webkit-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @-o-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
</style>