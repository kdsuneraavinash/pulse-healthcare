import 'package:flutter/material.dart';
import 'package:fancy_bottom_navigation/fancy_bottom_navigation.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:provider/provider.dart';
import 'package:pulse_healthcare/profile.dart';
import 'package:pulse_healthcare/timeline.dart';

import 'logic/theme.dart';
import 'logic/user.dart';

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
        leading: Provider.of<UserManager>(context).pending
            ? Center(child: CircularProgressIndicator())
            : Icon(FontAwesomeIcons.medkit),
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
          Center(
              child:
                  Icon(FontAwesomeIcons.cogs, size: 72.0, color: Provider.of<ThemeStash>(context).primaryColor)),
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
          TabData(iconData: FontAwesomeIcons.pills, title: "Prescriptions"),
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
    );
  }
}
