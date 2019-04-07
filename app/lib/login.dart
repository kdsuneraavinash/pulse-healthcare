import 'package:flare_flutter/flare_actor.dart';
import 'package:flutter/material.dart';

class LoginScreen extends StatelessWidget {
  LoginScreen({Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: <Widget>[
          Container(
            child: FlareActor(
              "assets/login_screen.flr",
              alignment: Alignment.center,
              fit: BoxFit.cover,
              animation: "rotate",
            ),
          ),
          Center(
            child: ListView(
              shrinkWrap: true,
              children: <Widget>[
                LoginForm(),
              ],
            ),
          )
        ],
      ),
    );
  }
}

class LoginForm extends StatefulWidget {
  @override
  _LoginFormState createState() => _LoginFormState();
}

class _LoginFormState extends State<LoginForm> {
  final _formKey = GlobalKey<FormState>();

  Widget _buildFormTextBox(
      String hintText, FormFieldValidator<String> validator, [isPassword = false]) {
    return Padding(
      padding: const EdgeInsets.all(8.0),
      child: TextFormField(
        obscureText: isPassword,
        decoration: InputDecoration(
          border: OutlineInputBorder(),
          hintText: hintText,
          fillColor: Colors.white,
          filled: true,
        ),
        validator: validator,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      alignment: Alignment.center,
      margin: EdgeInsets.all(16.0),
      child: Form(
        key: _formKey,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: <Widget>[
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Text(
                "MediKit",
                style: TextStyle(
                  fontSize: 72,
                  color: Colors.white,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ),
            _buildFormTextBox("PATIENT-ID", (value) {
              if (value.isEmpty) {
                return 'Please enter your PATIENT-ID';
              }
            }),
            _buildFormTextBox(
              "PASSWORD",
              (value) {
                if (value.isEmpty) {
                  return 'Please enter password';
                }
              },
              true
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: <Widget>[
                Padding(
                  padding: const EdgeInsets.all(8.0),
                  child: RaisedButton(
                    onPressed: () {
                      if (_formKey.currentState.validate()) {
                        Scaffold.of(context).showSnackBar(
                            SnackBar(content: Text('Processing Data')));
                      }
                    },
                    color: Colors.lightGreen,
                    child: Padding(
                      padding: const EdgeInsets.all(20.0),
                      child: Text(
                        'Login',
                        style: TextStyle(
                          fontSize: 18,
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
