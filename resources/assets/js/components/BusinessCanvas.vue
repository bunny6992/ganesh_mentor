<script>
import draggable from 'vuedraggable';
    export default {
        components: {
            draggable
        },
        data: () => {
            return {
              activeItem: '',
              currentShareProject: '',
              shareModels: [],
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
              newModel: {
                type: '',
                index: '',
                projectId: ''
              },
              numbers: [
                  {
                      val: 'one',
                      edit: false
                  },
                  { val: 'two',
                    edit: false
                  },
                  {
                      val: 'three',
                      edit: false
                  }
              ],
              clickToClose: false,
              vueOptions: {},
              sharedProjects: [],
              canEdit: 1,
              dragOptions: {
                group: 'people'
              }
            }
        },

        mounted() {
          this.getProjects();
        },

        computed: {
          
        },

        methods: {
          w3_open() {
              document.getElementById("mySidebar").style.display = "block";
          },
          w3_close() {
              document.getElementById("mySidebar").style.display = "none";
          },
          getProjects() {
            axios.get('/getProjects')
              .then((response) => {
                  this.projects = response.data.projects;
                  var sharedItems = response.data.shared_items;
                  this.sharedProjects = _.groupBy(sharedItems, 'project_id');
              })
              .catch(function (error) {
                  console.log(error);
              });
          },

          setCurrentProject(project, index) {
            _.forEach(project.models, (model) => {
              model.editName = 0;
            });
            this.currentProject = project;
            this.currentProject.index = index;
          },

          saveNewModelName(model) {
            axios.post('/saveNewModelName', {id: model.id, name: model.name})
              .then((response) => {
                  model.editName = 0;
              })
              .catch(function (error) {
                  console.log(error);
              });
          },

          getCanvas(id, modelType, permission = 1) {
            this.canEdit = permission;
            axios.post('/getCanvas', {id: id})
              .then((response) => {
                  this.setCurrentModel(modelType);
                  this.currentModel.id = id;
                  var items = response.data;
                  _.forEach(items, (item) => {
                    this.currentModel[item.type].push({id: item.id, title: item.title, body: item.body, type: item.type});
                  });
                  _.forEach(this.currentProject.models, (model) => {
                    if (model.id == id) {
                      this.currentModel.name = model.name;
                    }
                  });
                  this.activeItem = 'item' + id;
              })
              .catch(function (error) {
                  console.log(error);
              });
          },

          setCurrentModel(type) {
            if (type == 'canvas') {
              this.currentModel = {
                type: 'canvas', id: '',  partners: [], activities: [], resources: [],
                propositions: [], relationships: [], channels: [],
                segments: [], cost: [], revenue: [], brainstorming: [],
                newCanvas: {
                  type: '', title: '', body: ''
                }
              }
            } else if (type == 'swot') {
              this.currentModel = {
                type: 'swot', id: '', strengths: [], weakness: [], opportunities: [],
                threats: [], brainstorming: [],
                newCanvas: {
                  type: '',
                  title: '',
                  body: ''
                }
              }
            }
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
              Gym.gtmTracking.addNewProjectButtonClicked();
              Gym.mixpanel.addNewProjectButtonClicked();
              if (result.value) {
                axios.post('/addNewProject', {name: result.value, type: 'canvas'})
                  .then((response) => {
                      if (response.data.success) {
                          this.getProjects();
                          this.$swal({
                            title: 'New Project Added!',
                            timer: 1000,
                            onOpen: () => {
                              this.$swal.showLoading()
                            }
                        });
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

          addNewProjectModel(type) {
            var id = this.currentProject.id;
            var index = this.currentProject.index;
            this.$swal({
              title: 'Name',
              input: 'text',
              showCancelButton: true,
              confirmButtonText: 'Submit',
              allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {
              if (result.value) {
                axios.post('/addNewProjectModel', {name: result.value, projectId: id, type: type})
                  .then((response) => {
                      if (response.data.success) {
                        Gym.gtmTracking.newModelCreated(type);
                        Gym.mixpanel.newModelCreated(type);
                        this.setCurrentModel(type);
                        this.currentProject.models.push({name: response.data.model.name, id: response.data.model.id, type: response.data.model.type});
                        this.currentModel.id = response.data.model.id;
                        this.activeItem = 'item' + this.currentModel.id;           
                        this.$swal({
                            title: 'New Model Added!',
                            timer: 1000,
                            onOpen: () => {
                              this.$swal.showLoading()
                            }
                        });
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

          showAddNewModel(index, projectId) {
            this.$modal.show('add-model');
            this.newModel.index = index;
            this.newModel.projectId = projectId;
          },

          addNewModel() {
            console.log(this.newModel.type);
            this.$modal.hide('add-model');
            this.addNewProjectModel(this.newModel.type);
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
                        this.setCurrentModel(this.currentModel.type);
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

          shareProject(id) {
            this.currentShareProject = '';
            _.forEach(this.projects, (project) => {
              if (project.id == id) {
                this.currentShareProject = project;
              }
            });
            _.forEach(this.currentShareProject.models, (model) => {
              model.permission = 0;
              model.selected = 0;
            });
            this.currentShareProject.emails = [{value: ''}];
            this.$modal.show('share-project');
          },

          share() {
            var shareProject = {emails: this.currentShareProject.emails, id: this.currentShareProject.id, models: []};
            _.forEach(this.currentShareProject.models, (model) => {
              if (model.selected) {
                shareProject.models.push(model);
              }
            });

            this.$modal.hide('share-project');
            this.$swal({
              title: 'Sharing your project!',
              timer: 1000,
              onOpen: () => {
                this.$swal.showLoading()
              }
            });
            
            axios.post('/shareProject', {shareProject})
              .then((response) => {
                  this.$swal({
                    position: 'top-end',
                    type: response.data.type,
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 10000
                  })
                  if (response.data.type == 'error') {
                    this.$modal.show('share-project');
                  }
              })
              .catch(function (error) {
                  console.log(error);
              });
          },

          addReceiver() {
            var test = this.currentShareProject;
            this.currentShareProject = '';
            test.emails.push({value: ''});
            this.currentShareProject = test;
          },

          showModelDropdown(type) {
            var flag = 0;
            if (_.has(this.currentProject, 'models')) {
              _.forEach(this.currentProject.models, (model) => {
                if(model.type == type) {
                  flag = 1;
                  return false;
                }
              });
            }

            if (flag) {
              return true;
            }

            return false;            
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
