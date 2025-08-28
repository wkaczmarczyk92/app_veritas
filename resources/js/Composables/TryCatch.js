export async function try_catch(callback, store) {
    store.processing_store.start();
    var response = null;

    try {
        response = await callback()
        console.log("TryCatch Response:", response);
        // return response;
    } catch (error) {
        console.error("User Store Error:", error);
        store.alert_store.exception();
        return false;
    } finally {
        store.processing_store.stop();
    }

    return response;
}
