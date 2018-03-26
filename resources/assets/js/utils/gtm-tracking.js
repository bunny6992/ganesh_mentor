var Gym = Gym || {};

Gym.gtmTracking = (function (){

    /**
     * [_businessWorkoutToolsButtonClicked description]
     * @return {[type]} [description]
     */
    function _businessWorkoutToolsButtonClicked() {
        window.dataLayer.push({
            'event': 'workoutTools',
            'eventCategory': 'workoutTools',
            'eventAction': 'Business Workout Tools Button Clicked',
            'eventLabel': 'Business Workout Tools Button Clicked',
            'eventValue': window.activeUser
        });
    }

    /**
     * [_businessWorkoutToolsAddNewProjectButtonClicked description]
     * @return {[type]} [description]
     */
    function _addNewProjectButtonClicked() {
        window.dataLayer.push({
            'event': 'workoutTools',
            'eventCategory': 'workoutTools',
            'eventAction': 'Add New Project Button Clicked',
            'eventLabel': 'Add New Project Button Clicked',
            'eventValue': window.activeUser
        });
    }

    function _ganttChartLinkOpened() {
        window.dataLayer.push({
            'event': 'workoutTools',
            'eventCategory': 'workoutTools',
            'eventAction': 'User Opened Gantt Chart',
            'eventLabel': 'User Opened Gantt Chart',
            'eventValue': 'UserId:' + window.activeUser + ' User Opened Gannt Chart'
        });   
    }

    function _resourcesLinkOpened() {
        window.dataLayer.push({
            'event': 'workoutTools',
            'eventCategory': 'workoutTools',
            'eventAction': 'User Opened Resources Link',
            'eventLabel': 'User Opened Resources Link',
            'eventValue': 'UserId:' + window.activeUser + ' User Opened Resources Link'
        });
    }

    function _newModelCreated(model) {
        window.dataLayer.push({
            'event': 'workoutTools',
            'eventCategory': 'workoutTools',
            'eventAction': 'User Created ' + model,
            'eventLabel': 'User Created ' + model,
            'eventValue': 'UserId:' + window.activeUser + ' User Created ' + model 
        });
    }

    return {
        businessWorkoutToolsButtonClicked: _businessWorkoutToolsButtonClicked,
        addNewProjectButtonClicked: _addNewProjectButtonClicked,
        ganttChartLinkOpened: _ganttChartLinkOpened,
        resourcesLinkOpened: _resourcesLinkOpened,
        newModelCreated: _newModelCreated
    };
}());