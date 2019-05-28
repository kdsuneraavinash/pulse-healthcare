import 'package:flutter/material.dart';
import 'package:fancy_bottom_navigation/fancy_bottom_navigation.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:provider/provider.dart';
import 'package:pulse_healthcare/ui/profile.dart';
import 'package:pulse_healthcare/ui/search.dart';
import 'package:pulse_healthcare/ui/timeline.dart';

import 'package:pulse_healthcare/logic/theme/theme_stash.dart';
import 'package:pulse_healthcare/logic/api_controller/api_controller.dart';

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  PageController _pageController;
  GlobalKey _bottomNavigationKey = new GlobalKey();
  GlobalKey _profilePageKey = new GlobalKey();
  bool _isPageViewAnimating = false;

  @override
  void initState() {
    _pageController = new PageController();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("MedKit"),
        centerTitle: true,
        leading: Provider.of<APIController>(context).pending
            ? Center(child: CircularProgressIndicator())
            : null,
        actions: <Widget>[
          IconButton(
            onPressed: Provider.of<ThemeStash>(context).nextTheme,
            icon: Icon(Icons.palette),
          ),
        ],
      ),
      body: PageView(
        controller: _pageController,
        children: <Widget>[
          Center(child: TimelinePage()),
          Center(child: SearchScreen()),
          Center(child: ProfilePage(key: _profilePageKey)),
        ],
        onPageChanged: (position) {
          if (!_isPageViewAnimating) {
            final FancyBottomNavigationState fState =
                _bottomNavigationKey.currentState;
            fState.setPage(position);
          }
        },
      ),
      bottomNavigationBar: FancyBottomNavigation(
        key: _bottomNavigationKey,
        tabs: [
          TabData(iconData: Icons.timeline, title: "Timeline"),
          TabData(iconData: FontAwesomeIcons.search, title: "Search"),
          TabData(iconData: FontAwesomeIcons.userTie, title: "Profile"),
        ],
        onTabChangedListener: (position) {
          setState(() {
            _isPageViewAnimating = true;
            _pageController
                .animateToPage(position,
                    duration: Duration(milliseconds: 300),
                    curve: Curves.easeOut)
                .then((_) {
              setState(() {
                _isPageViewAnimating = false;
              });
            });
          });
        },
      ),
      drawer: _buildDrawer(),
    );
  }

  Widget _buildDrawer() {
    APIController apiController = Provider.of<APIController>(context);

    return Drawer(
      child: Column(
        children: <Widget>[
          UserAccountsDrawerHeader(
            accountEmail: Text(apiController.userId),
            accountName: Text(apiController.name),
            currentAccountPicture: CircleAvatar(
              child: Icon(
                FontAwesomeIcons.userTie,
                color: Theme.of(context).primaryColor,
                size: 40,
              ),
              backgroundColor: Colors.white,
            ),
          ),
          _buildListTile(
            "About",
            "About this app",
            FontAwesomeIcons.questionCircle,
            () {
              showAboutDialog(
                  context: context,
                  applicationIcon: Icon(
                    FontAwesomeIcons.medkit,
                    color: Provider.of<ThemeStash>(context)
                        .theme
                        .textColorOnScaffold,
                  ),
                  applicationName: "MediKit",
                  applicationVersion: "v1.0.0",
                  children: <Widget>[
                    Text(
                        "This is a app made for MediKit, an record keeping website for medical purposes.")
                  ]);
            },
          ),
          _buildListTile(
            "Logout",
            "Logout from the app",
            FontAwesomeIcons.signOutAlt,
            () => apiController.logout(),
          ),
        ],
      ),
    );
  }

  Widget _buildListTile(
      String title, String subtitle, IconData icon, VoidCallback onPressed) {
    return ListTile(
      title: Text(title),
      subtitle: Text(subtitle),
      leading: Icon(icon,
          color: Provider.of<ThemeStash>(context).theme.textColorOnScaffold),
      onTap: onPressed,
    );
  }
}
