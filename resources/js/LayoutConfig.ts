import {
  ArrowLeftStartOnRectangleIcon,
  CogIcon,
  UserCircleIcon,
} from '@heroicons/vue/24/outline';
import logo from '../images/icons/MCM-logo.webp';
import { FunctionalComponent } from 'vue';
import { Calendar, Home, Inbox, Search, Settings } from "lucide-vue-next"


interface NavigationItem {
  name: string;
  route: string;
  icon: FunctionalComponent;
}

const layoutNavigationItems: NavigationItem[] = [
];

const userNavigationItems: NavigationItem[] = [
  { name: 'Profile', route: 'pages.me.profile', icon: UserCircleIcon },
  { name: 'Settings', route: 'pages.me.settings', icon: CogIcon },
  { name: 'Sign out', route: 'logout', icon: ArrowLeftStartOnRectangleIcon },
];

const appName: string = 'MC Manager';
const appLogo: string = logo;

export {
  layoutNavigationItems,
  userNavigationItems,
  NavigationItem,
  appName,
  appLogo,
};
