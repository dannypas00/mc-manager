import { CloudIcon, HomeIcon } from '@heroicons/vue/24/outline';

interface NavigationItem {
  name: string;
  route: string;
  icon: any;
}

const layoutNavigationItems: NavigationItem[] = [
  { name: 'Page 1', route: 'page1', icon: HomeIcon },
  { name: 'Page 2', route: 'page2', icon: CloudIcon },
];

const appName: string = 'Test';
const appLogo: string = 'https://tailwindui.com/img/logos/mark.svg?color=white';

export {
  layoutNavigationItems,
  NavigationItem,
  appName,
  appLogo,
};
