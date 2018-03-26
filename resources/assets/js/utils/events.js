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