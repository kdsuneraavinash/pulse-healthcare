import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:provider/provider.dart';
import 'package:pulse_healthcare/logic/user.dart';
import 'package:pulse_healthcare/login.dart';

class ProfilePage extends StatelessWidget {
  ProfilePage({Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    double mediaQueryWidth = MediaQuery.of(context).size.width;
    UserManager userManager = Provider.of<UserManager>(context);

    return Container(
      child: ListView(
        children: <Widget>[
          Container(
            color: Theme.of(context).primaryColor,
            height: mediaQueryWidth / 2.5,
            padding: const EdgeInsets.all(16.0),
            alignment: Alignment.center,
            child: Icon(
              FontAwesomeIcons.userTie,
              size: mediaQueryWidth / 5,
              color: Colors.white,
            ),
          ),
          _buildListTile(
              FontAwesomeIcons.key, userManager.userId, "Patient ID"),
          _buildListTile(FontAwesomeIcons.userAlt, userManager.name, "Name"),
          _buildListTile(FontAwesomeIcons.idCard, userManager.nic, "NIC"),
          _buildListTile(FontAwesomeIcons.envelope, userManager.email, "Email"),
          _buildListTile(
              FontAwesomeIcons.phone, userManager.phoneNumber, "Phone Number"),
          _buildListTile(FontAwesomeIcons.city, userManager.address, "Address"),
          OutlineButton(
            onPressed: () => loggedOut(context),
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Text(
                'Logout',
                style: TextStyle(
                  fontSize: 18,
                  color: Theme.of(context).primaryColor,
                ),
              ),
            ),
          )
        ],
      ),
    );
  }

  Widget _buildListTile(IconData icon, String title, String subtitle) {
    return ListTile(
      leading: Icon(icon, color: Colors.black),
      title: Text(
        title,
        style: TextStyle(color: Colors.black, fontWeight: FontWeight.w700),
      ),
      subtitle: Text(subtitle),
    );
  }

  void loggedOut(BuildContext context) {
    Navigator.pushReplacement(
      context,
      PageRouteBuilder(
        pageBuilder: (_, animation, ___) => FadeTransition(
              opacity: animation,
              child: LoginScreen(),
            ),
      ),
    );
  }
}
