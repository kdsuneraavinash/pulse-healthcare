import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:provider/provider.dart';
import 'package:pulse_healthcare/logic/api_controller/api_controller.dart';
import 'package:pulse_healthcare/logic/theme/theme_stash.dart';
import 'package:pulse_healthcare/ui/login.dart';

class ProfilePage extends StatelessWidget {
  ProfilePage({Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    double mediaQueryWidth = MediaQuery.of(context).size.width;
    APIController apiController = Provider.of<APIController>(context);

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
          _buildListTile(FontAwesomeIcons.key, apiController.userId,
              "Patient ID", context),
          _buildListTile(
              FontAwesomeIcons.userAlt, apiController.name, "Name", context),
          _buildListTile(
              FontAwesomeIcons.idCard, apiController.nic, "NIC", context),
          _buildListTile(
              FontAwesomeIcons.envelope, apiController.email, "Email", context),
          _buildListTile(FontAwesomeIcons.phone, apiController.phoneNumber,
              "Phone Number", context),
          _buildListTile(
              FontAwesomeIcons.city, apiController.address, "Address", context),
          OutlineButton(
            onPressed: () => loggedOut(context),
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Text(
                'Logout',
                style: TextStyle(
                  fontSize: 18,
                  color: Provider.of<ThemeStash>(context)
                      .theme
                      .textColorOnScaffold,
                ),
              ),
            ),
          )
        ],
      ),
    );
  }

  Widget _buildListTile(
      IconData icon, String title, String subtitle, BuildContext context) {
    return ListTile(
      leading: Icon(icon,
          color: Provider.of<ThemeStash>(context).theme.textColorOnScaffold),
      title: Text(
        title,
        style: TextStyle(
            color: Provider.of<ThemeStash>(context).theme.textColorOnScaffold,
            fontWeight: FontWeight.w700),
      ),
      subtitle: Text(subtitle),
    );
  }

  void loggedOut(BuildContext context) {
    Provider.of<APIController>(context).logout();
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
