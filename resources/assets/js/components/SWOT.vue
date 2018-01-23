<script>
    export default {
        data: () => {
            return {
                currentSWOT: {},
                SWOTs: {},
                showBody: false,
                toastMessage: ''
            }
        },

        mounted() {
            this.getSWOTs();
        },

        methods: {
            getSWOTs () {
                axios.get('/getSWOT')
                .then((response) => {
                    this.SWOTs = response.data.swots;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            save () {
                if (!this.currentSWOT.title) {
                    this.toastMessage = "Title field is required.";
                    this.showToastMessage();
                    return;
                }
                var data = {
                    swot: this.currentSWOT
                };
                axios.post('/saveSWOT', data)
                    .then((response) => {
                        if (response.data.success) {
                            this.currentSWOT = response.data.swot;
                            this.toastMessage = "Saved Successfully";
                            this.showToastMessage();
                            this.getSWOTs();
                        }
                    })
                    .catch(function (error) {
                        this.toastMessage = error;
                        this.showToastMessage();
                    });
            },

            checkTitle () {
                this.titleError = false;
            },

            addNew () {
                this.showBody = true;
                this.currentSWOT = {};
                _.forEach(this.SWOTs, (swot) => {
                    $("#swot" + swot.id).removeClass("btn-success");
                    $("#swot" + swot.id).addClass("btn-dark");
                });
            },

            activeMe (swot) {
                this.currentSWOT = swot;
                this.showBody = true;
                _.forEach(this.SWOTs, (swot) => {
                    $("#swot" + swot.id).removeClass("btn-success");
                    $("#swot" + swot.id).addClass("btn-dark");
                });
                $("#swot" + swot.id).removeClass("btn-dark");
                $("#swot" + swot.id).addClass("btn-success");
            },

            showToastMessage (mess) {
                var x = document.getElementById("snackbar");
                x.className = "show";
                setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
            }
        }
    }
</script>
