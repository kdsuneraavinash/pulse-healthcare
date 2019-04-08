import 'package:flare_flutter/flare_actor.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

class ProfilePage extends StatelessWidget {
  ProfilePage({Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      child: ListView(
        children: <Widget>[
          Container(
              width: MediaQuery.of(context).size.width,
              height: MediaQuery.of(context).size.width *
                  MediaQuery.of(context).size.aspectRatio,
              margin: EdgeInsets.all(16.0),
              child: CircleAvatar(
                backgroundColor: Colors.purple,
                child: Container(
                  margin: EdgeInsets.all(16.0),
                  child: FlareActor(
                    "assets/bird.flr",
                    animation: "idle_breathe",
                    fit: BoxFit.contain,
                    alignment: Alignment.center,
                  ),
                ),
              )),
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
          _buildListTile(
              FontAwesomeIcons.phone, "076-8336850", "Phone NIcon(icon)umber"),
          SizedBox(
            height: 25,
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
