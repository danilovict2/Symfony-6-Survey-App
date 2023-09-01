import axios from "axios";
import { useToast, useVtEvents } from "vue-toastify";

export default function useSurvey() {
    function deleteSurvey(survey) {
        if (
            confirm(
                `Are you sure you want to delete this survey? Operation can't be undone!!`
            )
        ) {
            axios.post('/surveys/delete/' + survey.id)
                .then(() => {
                    useToast().notify({ body: `The survey was successfully deleted`, type: "success" });
                    useVtEvents().once('vtDismissed', () => {
                        window.location = `/surveys`;
                    });
                });
        }
    }

    return {deleteSurvey};
}