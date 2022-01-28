import router from 'system/router';
import index_handler from 'app/handler/index_handler';
import error_handler from "app/handler/error_handler";

router
    .notFound(() => {error_handler.handle()})
    .on('/', () => {index_handler.handle()})
    .resolve();
