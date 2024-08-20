import { ArrowLeftStartOnRectangleIcon } from '@heroicons/vue/24/outline';
import { FunctionalComponent } from 'vue';
import ApplicationLogo from '../images/icons/MCM-logo.webp';

interface NavigationItem {
  name: string;
  route: string;
  icon: FunctionalComponent;
}

const layoutNavigationItems: NavigationItem[] = [];

const userNavigationItems: NavigationItem[] = [
  { name: 'Sign out', route: 'logout', icon: ArrowLeftStartOnRectangleIcon },
];

const appName: string = 'Test';
const appLogo: string = ApplicationLogo;

export {
  layoutNavigationItems,
  userNavigationItems,
  NavigationItem,
  appName,
  appLogo,
};
