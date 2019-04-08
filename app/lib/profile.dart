import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

class ProfilePage extends StatelessWidget {
  ProfilePage({Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    double mediaQueryWidth = MediaQuery.of(context).size.width;
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
          _buildListTile(FontAwesomeIcons.key, "972502456V", "Patient ID"),
          _buildListTile(FontAwesomeIcons.userAlt, "Sunera Avinash", "Name"),
          _buildListTile(FontAwesomeIcons.idCard, "972502456V", "NIC"),
          _buildListTile(FontAwesomeIcons.birthdayCake, "22 years", "Age"),
          _buildListTile(FontAwesomeIcons.mars, "Male", "Gender"),
          _buildListTile(FontAwesomeIcons.language, "Sinhala", "Ethnicity"),
          _buildListTile(
              FontAwesomeIcons.city,
              "344/1, Moonamalgahawatta, Duwatemple Rd, Kalutara South.",
              "Address"),
          _buildListTile(FontAwesomeIcons.phone, "076-8336850", "Phone Number"),
          OutlineButton(
            onPressed: () {},
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
}
