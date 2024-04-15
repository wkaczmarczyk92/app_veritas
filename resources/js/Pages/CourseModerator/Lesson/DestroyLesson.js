import { AlertStore } from "@/Pinia/AlertStore"
import {
    router
} from '@inertiajs/vue3'

export async function destroy_lesson(lesson, compendium = true) {
    const alert_store = AlertStore()

    console.log(lesson)
    if (confirm('Na pewno chcesz usunąć lekcję?')) {
        let response = await axios.delete(route('course_moderator.lesson.destroy', {
            id: lesson.id
        }))

        response = response.data

        if (response.success) {
            let route_name = compendium ? 'compendium' : 'vademecum'
            router.visit(route(`course_moderator.${route_name}.show`, {
                id: lesson.lessonable.id
            }))
        }

        alert_store.pushAlert(response)
    }
}