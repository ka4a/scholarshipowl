@Library("jenkins-lib") _

def image       = new image()
def deploy      = new deploy()
def ingDomain   = deploy.getAppUrl().replace("https://", "")
def branchName  = "${BRANCH_NAME.replace("%2F", "_")}"

SOWLPipeline {
    node                             = "docker"
    cluster                          = "sowl-tech"
    appName                          = "sowl-dev"
    zone                             = "europe-west1-b"
    project                          = "sowl-tech"
    imageName                        = "php-fpm"
    namespace                        = "sowl-dev"
    chartName                        = "sowl-dev"
    replacements                     = [
                                            "dbHost" :     ["DB_HOST=sowl-dev-mysql", "DB_HOST=${image.getBranchName()}-mysql", "@WORKSPACE/.env.kubernetes"],
                                            "dbHostMaster"  : ["DB_HOST_MASTER=sowl-dev-mysql", "DB_HOST_MASTER=${image.getBranchName()}-mysql", "@WORKSPACE/.env.kubernetes"],
                                            "dbHostReplica" : ["DB_HOST_REPLICA=sowl-dev-mysql", "DB_HOST_REPLICA=${image.getBranchName()}-mysql", "@WORKSPACE/.env.kubernetes"],
                                            "dbEmailHost": ["DB_EMAILS_HOST=sowl-dev-mysql", "DB_EMAILS_HOST=${image.getBranchName()}-mysql", "@WORKSPACE/.env.kubernetes"],
                                            "appUrl":      ["APP_URL=willBeChanged", "APP_URL=${deploy.getAppUrl()}", "@WORKSPACE/.env.kubernetes"],
                                            "ingDomain":   ["@REPLACE_HOST", "${ingDomain}", "@WORKSPACE/kube/charts/sowl-dev/values.yaml"],
                                            "mysqlService":["mysqlService: will-be-replaced", "mysqlService: ${image.getBranchName()}-mysql", "@WORKSPACE/kube/charts/sowl-dev/values.yaml"],
                                            "mysqlInit":   ["@DBHOST", "${image.getBranchName()}-mysql", "@WORKSPACE/kube/charts/sowl-dev/templates/deployment.yaml"],
                                       ]
    serviceAccount                   = "jenkins-new@sowl-tech.iam.gserviceaccount.com"
    service_account_key_id           = "sowl-tech-jenkins2"
    waitTime                         = "1m"
    slack                            = true
    slackChannel                     = "#dev-builds"
    slackMsg                         = ":docker: STARTED: `${branchName}`"
    slackSuccessProdMsg              = ":docker: Dear @sowl-team  \nDeployed `${branchName}` \nto #URL\nHappy Testing!"
    slackSuccessDevMsg               = ":docker: Dear @sowl-team  \nDeployed `${branchName}` \nto #URL\nHappy Testing!"
    slackFailedMsg                   = ":docker-down: FAILED: `${branchName}`\n#BUILD_URL"
}