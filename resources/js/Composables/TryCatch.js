export async function try_catch(callback, store) {
    store.processing_store.start();

    try {
        await callback()
    } catch (error) {
        console.error("User Store Error:", error);
        store.alert_store.exception();
    } finally {
        store.processing_store.stop();
    }
}
