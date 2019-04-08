import 'package:flare_flutter/flare_actor.dart';
import 'package:flutter/material.dart';
import 'package:fancy_bottom_navigation/fancy_bottom_navigation.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  PageController _pageController;
  GlobalKey _bottomNavigationKey = new GlobalKey();
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
      ),
      body: PageView(
        controller: _pageController,
        children: <Widget>[
          Center(child: ProfilePage()),
          Center(child: Text("Page 2")),
          Center(child: Text("Page 3")),
        ],
        onPageChanged: (position) {
          if (!_isPageViewAnimating){
            final FancyBottomNavigationState fState = _bottomNavigationKey
                .currentState;
            fState.setPage(position);
          }
        },
      ),
      bottomNavigationBar: FancyBottomNavigation(
        key: _bottomNavigationKey,
        tabs: [
          TabData(iconData: FontAwesomeIcons.userTie, title: "Profile"),
          TabData(iconData: FontAwesomeIcons.pills, title: "Prescriptions"),
          TabData(iconData: Icons.search, title: "Search"),
        ],
        onTabChangedListener: (position) {
          setState(() {
            _isPageViewAnimating = true;
            _pageController.animateToPage(position,
                duration: Duration(milliseconds: 300), curve: Curves.easeOut)
                .then((_){
            setState(() {
              _isPageViewAnimating = false;
            });
            });
          });
        },
        circleColor: Colors.white,
        textColor: Colors.white,
        inactiveIconColor: Colors.white,
        barBackgroundColor: Colors.black,
        activeIconColor: Colors.black,
      ),
    );
  }
}

class ProfilePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      child: FlareActor(
        "assets/world.flr",
        alignment: Alignment.center,
        fit: BoxFit.cover,
        animation: "Preview2",
      ),
    );
  }
}
