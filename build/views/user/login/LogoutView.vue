<script setup>
import {useRouter} from "vue-router";
import useUserService from "@/composables/UserService";
import {onMounted} from "vue";
import {useToast} from "vue-toastification";

const router = useRouter();
const toast = useToast();
const {unLoadUser} = useUserService();

onMounted(() => {
  unLoadUser();
  toast.success('Abmeldung erfolgreich');
  router.push('/');
});
</script>
