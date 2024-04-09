import { ArrowLeftStartOnRectangleIcon, CogIcon, UserCircleIcon, TableCellsIcon, SpeakerWaveIcon } from '@heroicons/vue/24/outline';

interface NavigationItem {
  name: string;
  route: string;
  icon: any;
}

const layoutNavigationItems: NavigationItem[] = [
  { name: 'DataTable Example', route: 'page1', icon: TableCellsIcon },
  { name: 'Reverb Example', route: 'page2', icon: SpeakerWaveIcon },
];

const userNavigationItems: NavigationItem[] = [
  { name: 'Profile', route: 'me.profile', icon: UserCircleIcon },
  { name: 'Settings', route: 'me.settings', icon: CogIcon },
  { name: 'Sign out', route: 'logout', icon: ArrowLeftStartOnRectangleIcon }
];

const appName: string = 'Test';
const appLogo: string = 'https://tailwindui.com/img/logos/mark.svg?color=white';

export {
  layoutNavigationItems,
  userNavigationItems,
  NavigationItem,
  appName,
  appLogo,
};
