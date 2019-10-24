# Directory Structure

- [bin](./bin) - executable files and other important files related to the application initialization
- [certificates](./certificates) - SSL certificates
- [logs](./logs) - error logs
- [public](./public) - the publically accessible static resources - these are generated from [public_src](./public_src/)
- [src](./src) - the application source (this code is being executed, no other build steps are required)
- [startup_generated](./startup_generated) - certain files generated at application startup - these are not used by the application but are generated for debug purpose