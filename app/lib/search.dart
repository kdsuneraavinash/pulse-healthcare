import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:floating_search_bar/floating_search_bar.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:provider/provider.dart';
import 'package:pulse_healthcare/logic/doctor.dart';
import 'package:pulse_healthcare/logic/user_manager.dart';

class SearchScreen extends StatefulWidget {
  @override
  _SearchScreenState createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  String _text;
  List<Doctor> _doctors;
  bool _isSearching;

  _SearchScreenState() {
    _doctors = [];
    _isSearching = false;
    _text = '';
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 8.0),
      child: FloatingSearchBar.builder(
        itemCount: _isSearching ? 1 : (_doctors.length + 1),
        itemBuilder: (BuildContext context, int index) {
          return _isSearching
              ? Center(
                  child: Padding(
                  padding: const EdgeInsets.all(32.0),
                  child: CircularProgressIndicator(),
                ))
              : index == _doctors.length
                  ? SizedBox(
                      height: 100,
                    )
                  : ListTile(
                      leading: Icon(FontAwesomeIcons.userMd),
                      title: Text("Dr. ${_doctors[index].displayName}"),
                      subtitle: Text(_doctors[index].accountId),
                      trailing: Chip(
                        label: Text(_doctors[index].category),
                      ),
                      onTap: () {
                        Doctor doctor = _doctors[index];
                        showModalBottomSheet(
                            context: context,
                            builder: (_) => Column(
                                  children: <Widget>[
                                    Padding(
                                      padding: const EdgeInsets.all(8.0),
                                      child: Text(
                                        "Dr. ${doctor.displayName}",
                                        style: TextStyle(
                                            color: Colors.black,
                                            fontWeight: FontWeight.w700,
                                            fontSize: 22),
                                      ),
                                    ),
                                    Divider(),
                                    Expanded(
                                      child: ListView(
                                        children: <Widget>[
                                          _buildListTile(
                                              FontAwesomeIcons.userMd,
                                              'Full Name',
                                              doctor.fullName),
                                          _buildListTile(FontAwesomeIcons.key,
                                              'Account ID', doctor.accountId),
                                          _buildListTile(
                                              FontAwesomeIcons.idCard,
                                              'NIC',
                                              doctor.nic),
                                          _buildListTile(
                                              FontAwesomeIcons.hospital,
                                              'SLMC ID',
                                              doctor.slmcId),
                                          _buildListTile(FontAwesomeIcons.book,
                                              'Category', doctor.category),
                                          _buildListTile(
                                              FontAwesomeIcons.envelope,
                                              'E Mail',
                                              doctor.email),
                                          _buildListTile(
                                              FontAwesomeIcons.phone,
                                              'Phone Number',
                                              doctor.phoneNumber),
                                        ],
                                      ),
                                    )
                                  ],
                                ));
                      },
                    );
        },
        trailing: CircleAvatar(
          child: IconButton(
            icon: Icon(
              Icons.search,
              color: Colors.white,
            ),
            onPressed: () async {
              if (_text == '') return;
              setState(() {
                _isSearching = true;
              });

              _doctors = await Provider.of<UserManager>(context)
                      .getSearchResults(_text) ??
                  [];
              setState(() {
                _isSearching = false;
              });
            },
            splashColor: Theme.of(context).primaryColor,
          ),
          backgroundColor: Theme.of(context).primaryColor,
        ),
        onChanged: (String value) {
          _text = value;
        },
        decoration: InputDecoration.collapsed(
          hintText: "Search Doctor by Name",
        ),
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
      subtitle: Text(subtitle, style: TextStyle(color: Colors.black)),
    );
  }
}
