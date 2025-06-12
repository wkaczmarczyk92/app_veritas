export default class FileHanlder {
    files = []
    invalid_files = []
    alert_store = null
    event = null

    constructor(alert_store: object, files: array) {
        this.alert_store = alert_store
        this.files = files
    }

    exists(file: any) {
        return this.files.some(f => f.name === file.name)
    }

    push(file: any) {
        if (!this.check_type(file)) {
            this.invalid_files.push(file.name)
            return
        }

        this.files.push({
            name: file.name.replace(/\.[^/.]+$/, ''),
            size: file.size,
            file: file,
            // opt_for: null,
            type: file.type
        })

        console.log(this.files)
    }

    check_type(file: any) {
        return !(file.type == '')
    }

    drop(e: any) {
        if (e.dataTransfer.items) {
            [...e.dataTransfer.items].forEach((item, i) => {
                if (item.kind === "file") {
                    const file = item.getAsFile();

                    if (!this.exists(file)) {
                        this.push(file)
                    }
                }
            });
        } else {
            [...e.dataTransfer.files].forEach((file, i) => {
                if (!this.exists(file)) {
                    this.push(file)
                }
            });
        }

        this.check_for_invalid_files()
    }

    file_change(e: any) {
        for (let i = 0; i < e.target.files.length; i++) {
            if (!this.exists(e.target.files[i])) {
                this.push(e.target.files[i])
            }
        }

        this.check_for_invalid_files()
    }

    check_for_invalid_files() {
        if (this.invalid_files.length > 0) {
            let file_names_string = this.invalid_files.join(', ')
            this.alert_store.pushAlert('danger', `Pliki: ${file_names_string} mają nieprawidłowy format i zostały odrzucone!`)
            this.invalid_files = []
        }
    }

    get() {
        return this.files
    }

    type(file: object) {
        if (file.mime_type) {
            if (file.mime_type.type.startsWith('image/')) {
                return 'image';
            } else if (file.mime_type.type.startsWith('video/')) {
                return 'video';
            } else if (file.mime_type.type.startsWith('audio/')) {
                return 'audio';
            }
        }
        return 'brak mime type';

    }
}