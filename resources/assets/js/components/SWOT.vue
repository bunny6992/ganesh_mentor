<script>
    export default {
        data: () => {
            return {
                currentSWOT: {},
                SWOTs: {},
                showBody: false
            }
        },

        mounted() {
            axios.get('/getSWOT')
                .then((response) => {
                    this.SWOTs = response.data.swots;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        methods: {
            save () {
                var data = {
                    swot: this.currentSWOT
                };
                axios.post('/saveSWOT', data)
                    .then((response) => {
                        if (response.data.success) {
                            this.currentSWOT = response.data.swot;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            addNew () {
                this.showBody = true;
                this.currentSWOT = {};
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
            }
        }
    }
</script>
