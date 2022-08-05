<script setup>
import {useRouter} from "vue-router";
import useUserService from "@/composables/UserService";
import {onMounted} from "vue";

const router = useRouter();
const {unLoadUser} = useUserService();

onMounted(() => {
  unLoadUser();
  router.push('/');
});
</script>
