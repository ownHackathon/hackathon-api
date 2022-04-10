<script setup>
import {useRouter} from "vue-router";
import useUser from "@/composables/user";
import {onMounted} from "vue";

const router = useRouter();
const {unLoadUser} = useUser();

onMounted(() => {
  unLoadUser();
  localStorage.removeItem('token');
  router.push('/');
});
</script>
