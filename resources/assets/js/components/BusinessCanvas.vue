<script>
import draggable from 'vuedraggable';
    export default {
        components: {
            draggable
        },
        data: () => {
            return {
              activeItem: '',
              projects: [],
              models: [],
              currentProject: [],
              editCanvas: {
                id: '',
                type: '',
                title: '',
                body: ''
              },
              currentModel: {
                id: '',
                partners: [],
                activities: [],
                resources: [],
                propositions: [],
                relationships: [],
                channels: [],
                segments: [],
                cost: [],
                revenue: [],
                brainstorming: [],
                newCanvas: {
                  type: '',
                  title: '',
                  body: ''
                }
              },
              newCanvas: {
                type: '',
                title: '',
                body: ''
              },
              clickToClose: false,
              vueOptions: {},
            }
        },

        mounted() {
          this.getProjects();
        },

        methods: {

          getProjects() {
            axios.get('/getProjects')
              .then((response) => {
                  this.projects = response.data;
              })
              .catch(function (error) {
                  console.log(error);
              });
          },

          getCanvas(id) {
            axios.post('/getCanvas', {id: id})
              .then((response) => {
                  this.resetCurrentModel();
                  this.currentModel.id = id;
                  var items = response.data;
                  _.forEach(items, (item) => {
                    this.currentModel[item.type].push({id: item.id, title: item.title, body: item.body, type: item.type});
                  });
                  this.activeItem = 'item' + id;
              })
              .catch(function (error) {
                  console.log(error);
              });
          },

          resetCurrentModel() {
            this.currentModel = {
                id: '',  partners: [], activities: [], resources: [],
                propositions: [], relationships: [], channels: [],
                segments: [], cost: [], revenue: [], brainstorming: [],
                newCanvas: {
                  type: '', title: '', body: ''
                }
              }
          },

          addNewProject () {
            this.$swal({
              title: 'Project Name',
              input: 'text',
              showCancelButton: true,
              confirmButtonText: 'Submit',
              allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {
              if (result.value) {
                axios.post('/addNewProject', {name: result.value, type: 'canvas'})
                  .then((response) => {
                      if (response.data.success) {
                          this.projects.push({name: response.data.project.name, id: response.data.project.id, models: []});
                          this.$swal({
                            type: 'success',
                            title: 'New Project Added!',
                            html: 'Project Name: ' + result.value
                          })
                      }
                  })
                  .catch(function (error) {
                      console.log(error);
                  });
              }
            });
          },

          isActive: function (menuItem) {
              return this.activeItem === menuItem;
          },

          addNewProjectModel(id, index) {
            console.log(id);
            this.$swal({
              title: 'SWOT Model Name',
              input: 'text',
              showCancelButton: true,
              confirmButtonText: 'Submit',
              allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {
              if (result.value) {
                axios.post('/addNewProjectModel', {name: result.value, projectId: id})
                  .then((response) => {
                      if (response.data.success) {
                        this.projects[index].models.push({name: response.data.model.name, id: response.data.model.id});
                        this.currentModel.id = response.data.model.id;           
                        this.$swal({
                          type: 'success',
                          title: 'New SWOT Model Added!',
                          html: 'Canvas Name: ' + result.value
                        })
                      }    
                  })
                  .catch(function (error) {
                      console.log(error);
                  });
              }
            });
          }, 

          addModelItem(type) {
            this.newCanvas = {
              type: type,
              title: '',
              body: '',
              modelId: this.currentModel.id,
            }
            this.$modal.show('new-canvas');
          },

          addNewCanvas() {
            axios.post('/addNewModelItem', this.newCanvas)
              .then((response) => {
                  if (response.data.success) {
                    this.currentModel[this.newCanvas.type].push(response.data.item);
                    this.$modal.hide('new-canvas');
                  }    
              })
              .catch(function (error) {
                  console.log(error);
              });
          },

          updateSections(ev) {
            
            if (ev.from.id != ev.to.id) {
              var updateItem = {};
              _.forEach(this.currentModel[ev.to.id], (item) => {
                if (item.type == ev.from.id) {
                  updateItem = item;
                }
              });
              console.log(updateItem);
              axios.post('/updateModelItem', {modelId: updateItem.id, type: ev.to.id})
                .then((response) => {
                  if (response.data.success) {
                        this.$modal.hide('edit-canvas');
                        this.$swal({
                          title: 'Updating your Workspace!',
                          timer: 500,
                          onOpen: () => {
                            this.$swal.showLoading()
                          }
                        });
                        this.getCanvas(this.currentModel.id);
                        this.resetCurrentModel();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
          },

          deleteItem(id) {
            this.$swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                axios.post('/deleteItem', {id: id})
                  .then((response) => {
                      if (response.data.success) {
                        this.$swal({
                          title: 'Updating your Workspace!',
                          timer: 1000,
                          onOpen: () => {
                            this.$swal.showLoading()
                          }
                        });
                        this.getCanvas(this.currentModel.id);
                        this.resetCurrentModel();
                      }    
                  })
                  .catch(function (error) {
                      console.log(error);
                  });
              }
            })
          },

          editItem(element, index) {
            // let formattedBody = element.body.replace(/"/g, "'");
            this.editCanvas = {
              id: element.id,
              type: element.type,
              title: element.title,
              body: element.body,
              modelId: this.currentModel.id,
              index: index
            }
            this.$modal.show('edit-canvas');
          },

          saveCanvas() {
            console.log(this.editCanvas);
            axios.put('/saveModelItem', {id: this.editCanvas.id, body: this.editCanvas.body, title: this.editCanvas.title})
                .then((response) => {
                    if (response.data.success) {
                        this.$modal.hide('edit-canvas');
                        this.$swal({
                          title: 'Updating your Workspace!',
                          timer: 1000,
                          onOpen: () => {
                            this.$swal.showLoading()
                          }
                        });
                        this.getCanvas(this.currentModel.id);
                        this.resetCurrentModel();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
          },

          drowdownHREF(id) {
            return '#model-items-' + id;
          },

          drowdownId(id) {
            return 'model-items-' + id;
          }
        }
    }
</script>
