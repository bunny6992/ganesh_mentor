var Gym = Gym || {};

Gym.events = (function () {
    // Called on document ready
    $(function () {
        // Event handler for businessWorkoutToolsBtn
        _workoutToolsBtnClicked();

        // Gantt Chart Opening
        _projectGanttChartLinkClicked();

        _projectResourcesLinkClicked();
    });

    function _workoutToolsBtnClicked() {
        $('.gtm-business-workout-tools-btn').on('click', function(e) {
            e.preventDefault();
            Gym.gtmTracking.businessWorkoutToolsButtonClicked();
            Gym.mixpanel.businessWorkoutToolsButtonClicked();
            window.location.href = $(this).data('href');
        });
    }

    function _projectGanttChartLinkClicked() {
        $(document).on('click', '.gtm-project-gantt-chart-link', function(e) {
            e.preventDefault();
            Gym.gtmTracking.ganttChartLinkOpened();
            Gym.mixpanel.ganttChartLinkOpened();
            window.open($(this).data('href'), '_blank');
        });
    }

    function _projectResourcesLinkClicked() {
        $(document).on('click', '.gtm-project-resources-link', function(e) {
            e.preventDefault();
            Gym.gtmTracking.resourcesLinkOpened();
            Gym.mixpanel.resourcesLinkOpened();
            window.open($(this).data('href'), '_blank');
        });
    }

}());
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
Gym = Gym || {};

Gym.mixpanel = (function (){
    function _businessWorkoutToolsButtonClicked() {
        mixpanel.track(
            "Workout Tools Button Clicked",
            {"UserId": window.activeUser}
        );
    }

    function _ganttChartLinkOpened() {
        mixpanel.track(
            "Project Gantt Chart Opened",
            {"UserId": window.activeUser}
        );
    }

    function _resourcesLinkOpened() {
         mixpanel.track(
            "Project Resources Opened",
            {"UserId": window.activeUser}
        );
    }

    function _newModelCreated(type) {
        mixpanel.track(
            "New Model Created",
            {"UserId": window.activeUser, "Type": type}
        );
    }

    function _addNewProjectButtonClicked() {
        mixpanel.track(
            "New Project Created",
            {"UserId": window.activeUser}
        );   
    }

    return {
        businessWorkoutToolsButtonClicked: _businessWorkoutToolsButtonClicked,
        ganttChartLinkOpened: _ganttChartLinkOpened,
        resourcesLinkOpened: _resourcesLinkOpened,
        newModelCreated: _newModelCreated,
        addNewProjectButtonClicked: _addNewProjectButtonClicked
    };

}());